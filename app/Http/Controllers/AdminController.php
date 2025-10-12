<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Message;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        return view('admin.dashboard', compact('guests', 'messages', 'stats', 'eventStats'));
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
            'last_updated' => Carbon::now()->format('Y-m-d H:i:s')
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
            'last_updated' => Carbon::now()->format('Y-m-d H:i:s')
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
            'last_updated' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

    private function getEventStats()
    {
        // Cari event gedung dan rumah
        $gedungEvent = Event::where('event_key', 'gedung')->first();
        $rumahEvent = Event::where('event_key', 'rumah')->first();

        $eventStats = [];

        // Stats untuk Gedung - HANYA YANG HADIR
        if ($gedungEvent) {
            $gedungGuests = Guest::where('event_id', $gedungEvent->id)->get();
            $gedungAttendingGuests = $gedungGuests->where('attendance', 'Hadir');
            $gedungGuestIds = $gedungGuests->pluck('id')->toArray();

            $eventStats['gedung'] = [
                'name' => $gedungEvent->location,
                'total_guests' => $gedungAttendingGuests->count(),
                'total_messages' => Message::whereIn('guest_id', $gedungGuestIds)->count(),
                'attending_guests' => $gedungAttendingGuests->count(),
                'not_attending_guests' => $gedungGuests->where('attendance', 'Tidak Hadir')->count(),
                'opened_invitations' => $gedungGuests->where('is_opened', true)->count(),
                'not_opened_invitations' => $gedungGuests->where('is_opened', false)->count(),
                'guest_attends_total' => $gedungAttendingGuests->sum('guest_attends'),
                'recent_guests' => $gedungGuests->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
                'all_guests_count' => $gedungGuests->count(),
            ];
        } else {
            $eventStats['gedung'] = $this->getDefaultEventStats('Resepsi di Gedung');
        }

        // Stats untuk Rumah - HANYA YANG HADIR
        if ($rumahEvent) {
            $rumahGuests = Guest::where('event_id', $rumahEvent->id)->get();
            $rumahAttendingGuests = $rumahGuests->where('attendance', 'Hadir');
            $rumahGuestIds = $rumahGuests->pluck('id')->toArray();

            $eventStats['rumah'] = [
                'name' => $rumahEvent->location,
                'total_guests' => $rumahAttendingGuests->count(),
                'total_messages' => Message::whereIn('guest_id', $rumahGuestIds)->count(),
                'attending_guests' => $rumahAttendingGuests->count(),
                'not_attending_guests' => $rumahGuests->where('attendance', 'Tidak Hadir')->count(),
                'opened_invitations' => $rumahGuests->where('is_opened', true)->count(),
                'not_opened_invitations' => $rumahGuests->where('is_opened', false)->count(),
                'guest_attends_total' => $rumahAttendingGuests->sum('guest_attends'),
                'recent_guests' => $rumahGuests->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
                'all_guests_count' => $rumahGuests->count(),
            ];
        } else {
            $eventStats['rumah'] = $this->getDefaultEventStats('Resepsi di Rumah');
        }

        return $eventStats;
    }

    private function getDefaultEventStats($eventName)
    {
        return [
            'name' => $eventName,
            'total_guests' => 0,
            'total_messages' => 0,
            'attending_guests' => 0,
            'not_attending_guests' => 0,
            'opened_invitations' => 0,
            'not_opened_invitations' => 0,
            'guest_attends_total' => 0,
            'recent_guests' => 0,
            'all_guests_count' => 0,
        ];
    }

    public function storeGuest(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $eventKey = $request->event_type === 'p' ? 'gedung' : 'rumah';
                    $event = Event::where('event_key', $eventKey)->first();

                    if ($event) {
                        $existingGuest = Guest::where('event_id', $event->id)
                            ->where('name', $value)
                            ->first();

                        if ($existingGuest) {
                            $eventName = $eventKey === 'gedung' ? 'Gedung' : 'Rumah';
                            $fail("Nama tamu '{$value}' sudah terdaftar untuk acara {$eventName}.");
                        }
                    }
                }
            ],
            'event_type' => 'required|in:p,r',
            'guest_attends' => 'required|integer|min:1|max:10',
            'whatsapp_number' => 'nullable|string|max:20'
        ]);

        // Pastikan event ada
        $eventKey = $request->event_type === 'p' ? 'gedung' : 'rumah';
        $event = Event::where('event_key', $eventKey)->first();

        // Double check untuk memastikan tidak ada duplikasi (race condition)
        $existingGuest = Guest::where('event_id', $event->id)
            ->where('name', $request->name)
            ->first();

        if ($existingGuest) {
            $eventName = $eventKey === 'gedung' ? 'Gedung' : 'Rumah';
            return response()->json([
                'success' => false,
                'message' => "Nama tamu '{$request->name}' sudah terdaftar untuk acara {$eventName}."
            ], 422);
        }

        $guest = Guest::create([
            'name' => $request->name,
            'event_id' => $event->id,
            'code' => uniqid(),
            'guest_attends' => $request->guest_attends ?? 1,
            'whatsapp_number' => $request->whatsapp_number,
            'is_opened' => false
        ]);

        // Generate link
        $invitationUrl = null;
        if ($request->generate_link) {
            $baseUrl = url('/');
            $path = $request->event_type === 'p' ? 'p' : 'r';
            $invitationUrl = "$baseUrl/$path/invitation?to=$guest->name";
        }

        return response()->json([
            'success' => true,
            'message' => 'Tamu berhasil ditambahkan',
            'guest' => $guest,
            'invitation_url' => $invitationUrl
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
                    $eventKey = $request->event_type === 'p' ? 'gedung' : 'rumah';
                    $event = Event::where('event_key', $eventKey)->first();

                    if ($event) {
                        $existingGuest = Guest::where('event_id', $event->id)
                            ->where('name', $value)
                            ->where('id', '!=', $id)
                            ->first();

                        if ($existingGuest) {
                            $eventName = $eventKey === 'gedung' ? 'Gedung' : 'Rumah';
                            $fail("Nama tamu '{$value}' sudah terdaftar untuk acara {$eventName}.");
                        }
                    }
                }
            ],
            'guest_attends' => 'required|integer|min:1|max:10',
            'event_type' => 'required|in:p,r',
            'attendance' => 'nullable|in:Hadir,Tidak Hadir,Belum Konfirmasi',
            'whatsapp_number' => 'nullable|string|max:20'
        ]);

        $guest = Guest::findOrFail($id);

        $eventKey = $request->event_type === 'p' ? 'gedung' : 'rumah';
        $event = Event::where('event_key', $eventKey)->first();

        // Double check untuk memastikan tidak ada duplikasi (race condition)
        $existingGuest = Guest::where('event_id', $event->id)
            ->where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();

        if ($existingGuest) {
            $eventName = $eventKey === 'gedung' ? 'Gedung' : 'Rumah';
            return response()->json([
                'success' => false,
                'message' => "Nama tamu '{$request->name}' sudah terdaftar untuk acara {$eventName}."
            ], 422);
        }

        // Update data guest
        $guest->update([
            'name' => $request->name,
            'event_id' => $event->id,
            'guest_attends' => $request->guest_attends,
            'whatsapp_number' => $request->whatsapp_number,
            'attendance' => $request->attendance
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data tamu berhasil diperbarui',
            'guest' => $guest->load('event')
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
            'message' => 'Tamu berhasil dihapus'
        ]);
    }

    public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dihapus'
        ]);
    }

    public function exportFiltered(Request $request)
    {
        $eventFilter = $request->get('event', 'all');
        $statusFilter = $request->get('status', 'all');

        $query = Guest::with('event');

        // Apply event filter
        if ($eventFilter !== 'all') {
            $query->whereHas('event', function($q) use ($eventFilter) {
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

        $filename = "daftar-tamu-" . Carbon::now()->format('Y-m-d') . ".csv";
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
                $messages
            ]);
        }

        fclose($handle);

        return response()->streamDownload(function() use ($handle) {
            //
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportGuests()
    {
        $guests = Guest::with('messages')->get();

        $filename = "daftar-tamu-" . Carbon::now()->format('Y-m-d') . ".csv";
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
                $messages
            ]);
        }

        fclose($handle);

        return response()->streamDownload(function() use ($handle) {
            //
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function checkGuestExists(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'event_type' => 'required|in:p,r'
        ]);

        $eventKey = $request->event_type === 'p' ? 'gedung' : 'rumah';
        $event = Event::where('event_key', $eventKey)->first();

        if (!$event) {
            return response()->json([
                'exists' => false
            ]);
        }

        $existingGuest = Guest::where('event_id', $event->id)
            ->where('name', $request->name)
            ->first();

        if ($existingGuest) {
            $eventName = $eventKey === 'gedung' ? 'Gedung' : 'Rumah';
            return response()->json([
                'exists' => true,
                'message' => "Nama tamu '{$request->name}' sudah terdaftar untuk acara {$eventName}."
            ]);
        }

        return response()->json([
            'exists' => false
        ]);
    }
}
