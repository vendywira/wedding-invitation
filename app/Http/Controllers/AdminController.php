<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Message;
use App\Models\WeddingTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Eager load relationships
        $guests = Guest::with(['event', 'messages'])
            ->orderBy('created_at', 'desc')
            ->get();

        $messages = Message::with('guest')->orderBy('created_at', 'desc')->get();

        $attendingGuests = $guests->where('attendance', 'Hadir');

        $stats = [
            'total_guests' => $attendingGuests->count(),
            'total_people' => $attendingGuests->sum('guest_attends'),
            'total_messages' => Message::count(),
            'attending_guests' => $attendingGuests->count(),
            'not_attending_guests' => $guests->where('attendance', 'Tidak Hadir')->count(),
            'pending_guests' => $guests->where('attendance', 'Belum Konfirmasi')->count(),
            'all_guests_count' => $guests->count(),
        ];

        // Stats per event
        $eventStats = $this->getEventStats();

        // Template data
        $templates = WeddingTemplate::all();
        $activeTemplate = WeddingTemplate::where('is_active', true)->first();

        // Invitation groups (dynamic: gedung/rumah plus any custom group).
        $groups = Event::groups();

        return view('admin.dashboard', compact('guests', 'messages', 'stats', 'eventStats', 'templates', 'activeTemplate', 'groups'));
    }

    /**
     * Get dashboard data for AJAX requests
     */
    public function getDashboardData()
    {
        $guests = Guest::with(['event', 'messages'])->get();
        $attendingGuests = $guests->where('attendance', 'Hadir');

        // Stats overall
        $stats = [
            'total_guests' => $attendingGuests->count(),
            'total_people' => $attendingGuests->sum('guest_attends'),
            'total_messages' => Message::count(),
            'attending_guests' => $attendingGuests->count(),
            'not_attending_guests' => $guests->where('attendance', 'Tidak Hadir')->count(),
            'pending_guests' => $guests->where('attendance', null)->count(),
            'all_guests_count' => $guests->count(),
        ];

        // Stats per event
        $eventStats = $this->getEventStats();

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'eventStats' => $eventStats,
            'last_updated' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get guests data for AJAX requests
     */
    public function getGuestsData()
    {
        $guests = Guest::with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'guests' => $guests,
            'total_guests' => $guests->count(),
            'last_updated' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get messages data for AJAX requests
     */
    public function getMessagesData()
    {
        $messages = Message::with('guest')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'total_messages' => $messages->count(),
            'last_updated' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Statistics per invitation group, keyed by the group slug. Groups are fully
     * dynamic, so a newly created one shows up here automatically.
     *
     * @return array<string, array<string, mixed>>
     */
    private function getEventStats(): array
    {
        $eventStats = [];

        foreach (Event::groups() as $group) {
            $guests = Guest::where('event_id', $group->id)->get();
            $attendingGuests = $guests->where('attendance', 'Hadir');
            $guestIds = $guests->pluck('id')->all();

            $eventStats[$group->event_key] = [
                'name' => $group->label,
                'location' => $group->location,
                'is_default' => (bool) $group->is_default,
                'total_guests' => $attendingGuests->count(),
                'total_messages' => Message::whereIn('guest_id', $guestIds)->count(),
                'attending_guests' => $attendingGuests->count(),
                'not_attending_guests' => $guests->where('attendance', 'Tidak Hadir')->count(),
                'opened_invitations' => $guests->where('is_opened', true)->count(),
                'not_opened_invitations' => $guests->where('is_opened', false)->count(),
                'guest_attends_total' => $attendingGuests->sum('guest_attends'),
                'recent_guests' => $guests->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
                'all_guests_count' => $guests->count(),
            ];
        }

        return $eventStats;
    }

    /**
     * Resolve the invitation group a guest request targets.
     *
     * `event_type` carries the group slug (`events.event_key`); when it is
     * missing the default group is used.
     */
    private function resolveGroup(Request $request): ?Event
    {
        $slug = $request->get('event_type');

        if (! is_string($slug) || $slug === '') {
            return Event::defaultGroup();
        }

        return Event::where('event_key', $slug)->first();
    }

    public function storeGuest(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $event = $this->resolveGroup($request);

                    if (! $event) {
                        return;
                    }

                    $existingGuest = Guest::where('event_id', $event->id)
                        ->where('name', $value)
                        ->first();

                    if ($existingGuest) {
                        $fail("Nama tamu '{$value}' sudah terdaftar di grup \"{$event->label}\".");
                    }
                },
            ],
            'event_type' => 'required|string|exists:events,event_key',
            'guest_attends' => 'required|integer|min:1|max:10',
            'whatsapp_number' => 'nullable|string|max:20',
        ], [
            'event_type.exists' => 'Grup undangan yang dipilih tidak ditemukan.',
        ]);

        // Pastikan grup undangan ada
        $event = $this->resolveGroup($request);

        if (! $event) {
            return response()->json([
                'success' => false,
                'message' => 'Grup undangan tidak ditemukan.',
            ], 422);
        }

        // Double check untuk memastikan tidak ada duplikasi (race condition)
        $existingGuest = Guest::where('event_id', $event->id)
            ->where('name', $request->name)
            ->first();

        if ($existingGuest) {
            return response()->json([
                'success' => false,
                'message' => "Nama tamu '{$request->name}' sudah terdaftar di grup \"{$event->label}\".",
            ], 422);
        }

        $guest = Guest::create([
            'name' => $request->name,
            'event_id' => $event->id,
            'code' => uniqid(),
            'guest_attends' => $request->guest_attends ?? 1,
            'whatsapp_number' => $request->whatsapp_number,
            'is_opened' => false,
        ]);

        // Generate link — selalu mengikuti grup undangan tamu tersebut
        $invitationUrl = null;
        if ($request->generate_link) {
            $invitationUrl = $event->publicUrl($guest->name);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tamu berhasil ditambahkan',
            'guest' => $guest,
            'invitation_url' => $invitationUrl,
        ]);
    }

    public function updateGuest(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $id) {
                    $event = $this->resolveGroup($request);

                    if (! $event) {
                        return;
                    }

                    $existingGuest = Guest::where('event_id', $event->id)
                        ->where('name', $value)
                        ->where('id', '!=', $id)
                        ->first();

                    if ($existingGuest) {
                        $fail("Nama tamu '{$value}' sudah terdaftar di grup \"{$event->label}\".");
                    }
                },
            ],
            'guest_attends' => 'required|integer|min:1|max:10',
            'event_type' => 'required|string|exists:events,event_key',
            'attendance' => 'nullable|in:Hadir,Tidak Hadir,Belum Konfirmasi',
            'whatsapp_number' => 'nullable|string|max:20',
        ], [
            'event_type.exists' => 'Grup undangan yang dipilih tidak ditemukan.',
        ]);

        $guest = Guest::findOrFail($id);

        $event = $this->resolveGroup($request);

        if (! $event) {
            return response()->json([
                'success' => false,
                'message' => 'Grup undangan tidak ditemukan.',
            ], 422);
        }

        // Double check untuk memastikan tidak ada duplikasi (race condition)
        $existingGuest = Guest::where('event_id', $event->id)
            ->where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();

        if ($existingGuest) {
            return response()->json([
                'success' => false,
                'message' => "Nama tamu '{$request->name}' sudah terdaftar di grup \"{$event->label}\".",
            ], 422);
        }

        // Update data guest
        $guest->update([
            'name' => $request->name,
            'event_id' => $event->id,
            'guest_attends' => $request->guest_attends,
            'whatsapp_number' => $request->whatsapp_number,
            'attendance' => $request->attendance,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data tamu berhasil diperbarui',
            'guest' => $guest->load('event'),
        ]);
    }

    public function deleteGuest($id)
    {
        $guest = Guest::findOrFail($id);

        // Hapus messages terkait
        Message::where('guest_id', $id)->delete();

        $guest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tamu berhasil dihapus',
        ]);
    }

    public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dihapus',
        ]);
    }

    public function exportFiltered(Request $request)
    {
        $eventFilter = $request->get('event', 'all');
        $statusFilter = $request->get('status', 'all');

        $query = Guest::with('event');

        // Apply event filter
        if ($eventFilter !== 'all') {
            $query->whereHas('event', function ($q) use ($eventFilter) {
                $q->where('event_key', $eventFilter);
            });
        }

        // Apply status filter
        if ($statusFilter !== 'all') {
            if ($statusFilter === 'pending') {
                $query->where('attendance', '');
            } else {
                $query->where('attendance', $statusFilter);
            }
        }

        $guests = $query->get();

        $filename = 'daftar-tamu-'.Carbon::now()->format('Y-m-d').'.csv';
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Nama', 'WhatsApp', 'Kode', 'Jumlah Tamu', 'Konfirmasi', 'Dibuka', 'Dibuat', 'Diupdate', 'Pesan']);

        foreach ($guests as $guest) {
            $messages = $guest->messages->pluck('message')->implode('; ');
            fputcsv($handle, [
                $guest->name,
                $guest->whatsapp_number ?? '',
                $guest->code,
                $guest->guest_attends,
                $guest->attendance ?? 'Belum',
                $guest->is_opened ? 'Ya' : 'Tidak',
                $guest->created_at->format('d/m/Y H:i'),
                $guest->updated_at->format('d/m/Y H:i'),
                $messages,
            ]);
        }

        fclose($handle);

        return response()->streamDownload(function () {
            //
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportGuests()
    {
        $guests = Guest::with('messages')->get();

        $filename = 'daftar-tamu-'.Carbon::now()->format('Y-m-d').'.csv';
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Nama', 'WhatsApp', 'Kode', 'Jumlah Tamu', 'Konfirmasi', 'Dibuka', 'Dibuat', 'Diupdate', 'Pesan']);

        foreach ($guests as $guest) {
            $messages = $guest->messages->pluck('message')->implode('; ');
            fputcsv($handle, [
                $guest->name,
                $guest->whatsapp_number ?? '',
                $guest->code,
                $guest->guest_attends,
                $guest->attendance ?? 'Belum',
                $guest->is_opened ? 'Ya' : 'Tidak',
                $guest->created_at->format('d/m/Y H:i'),
                $guest->updated_at->format('d/m/Y H:i'),
                $messages,
            ]);
        }

        fclose($handle);

        return response()->streamDownload(function () {
            //
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function checkGuestExists(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'event_type' => 'required|string|exists:events,event_key',
        ], [
            'event_type.exists' => 'Grup undangan yang dipilih tidak ditemukan.',
        ]);

        $event = $this->resolveGroup($request);

        if (! $event) {
            return response()->json([
                'exists' => false,
            ]);
        }

        $existingGuest = Guest::where('event_id', $event->id)
            ->where('name', $request->name)
            ->first();

        if ($existingGuest) {
            return response()->json([
                'exists' => true,
                'message' => "Nama tamu '{$request->name}' sudah terdaftar di grup \"{$event->label}\".",
            ]);
        }

        return response()->json([
            'exists' => false,
        ]);
    }
}
