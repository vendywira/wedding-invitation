<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Message;
use App\Models\WeddingTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WeddingController extends Controller
{
    public function show(Request $request, $guest = null)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (!$template) {
            abort(404, 'No active template found');
        }

        $event = $this->resolveGroup($request);

        if (!$event) {
            abort(404, 'Undangan belum diatur');
        }

        $eventKey = $event->event_key;
        $guestData = null;

        if ($event->event_date) {
            $event->event_date_time_start = Carbon::parse($event->event_date . ' ' . $event->start_time)
                ->format('Y/m/d H:i:s');
        }

        if ($guest) {
            $guestData = Guest::where('code', $guest)->first();
        }

        // Jika tidak ada guest dari parameter, cek dari query string
        if (!$guestData && $request->has('to')) {
            $guestName = urldecode($request->get('to'));

            // Tamu dicari di grup undangan ini dulu: satu nama bisa terdaftar
            // di beberapa grup (mis. tamu yang diundang ke gedung dan ke rumah).
            $guestData = Guest::where('event_id', $event->id)
                ->where('name', 'like', '%' . $guestName . '%')
                ->first()
                ?? Guest::where('name', 'like', '%' . $guestName . '%')->first();

            if (!$guestData) {
                // Tamu yang membuka lewat link grup ini otomatis terdaftar di grup tersebut.
                $guestData = new Guest([
                    'name' => $guestName,
                    'event_id' => $event->id,
                    'code' => uniqid(),
                    'guest_attends' => 1,
                    'is_opened' => true
                ]);
                $guestData->save();
            }
        }

        if (!$guestData) {
            $guestData = new Guest([
                'name' => 'Tamu Undangan',
                'code' => uniqid(),
                'guest_attends' => 1,
            ]);
        }

        // `id` dipakai sebagai tie-breaker supaya urutan ucapan stabil — tanpa
        // itu ucapan yang `created_at`-nya sama bisa tertukar antar halaman
        // saat daftar ucapan diambil bertahap.
        $messages = Message::orderByDesc('created_at')->orderByDesc('id')->get();
        $metaData = $this->generateMetaData($request, $event, $guestData, $eventKey, $template);

        $viewPath = "wedding.templates.{$template->slug}.index";

        return response()
            ->view($viewPath, compact('guestData', 'messages', 'event', 'metaData', 'template'))
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('X-Robots-Tag', $metaData['robots_meta']);
    }

    /**
     * Which invitation group the current URL points at.
     *
     * `/{slug}/invitation` → the group that owns that slug, while the plain
     * `/invitation` link renders the group flagged as default.
     */
    private function resolveGroup(Request $request): ?Event
    {
        $segments = array_values(array_filter(
            explode('/', trim((string) $request->getPathInfo(), '/'))
        ));

        if (count($segments) >= 2 && end($segments) === 'invitation') {
            return Event::where('event_key', $segments[0])->first();
        }

        return Event::defaultGroup();
    }

    private function generateMetaData(Request $request, $event, $guestData, $eventKey, $template = null)
    {
        $currentUrl = url()->current();
        $hasToParam = $request->has('to');
        $guestName = $guestData->name;
        $location = $event->location ?? '';
        $eventDateFormatted = $event->event_date ?
            Carbon::parse($event->event_date)->locale('id')->translatedFormat('l, j F Y') :
            'Tanggal belum ditentukan';
        $path = $event->event_key;
        $weddingEmoji = '💍';

        // Get couple names from template
        $brideName = $template ? $template->getSetting('bride_name', 'Mempelai') : 'Mempelai';
        $groomName = $template ? $template->getSetting('groom_name', '') : '';
        $coupleName = $groomName ? "{$brideName} & {$groomName}" : $brideName;

        if ($hasToParam && $guestName !== 'Tamu Undangan') {
            $title = "{$weddingEmoji} Undangan Untuk {$guestName}";
            $description = "{$guestName}, Anda diundang secara khusus! 🎉 Dalam pernikahan {$coupleName}, {$eventDateFormatted}. Konfirmasi kehadiran Anda!";
            $ogTitle = "{$weddingEmoji} Undangan Untuk {$guestName}";
            $ogDescription = "🎊 {$guestName}, Anda diundang! Dalam pernikahan {$coupleName}. {$eventDateFormatted}. Buka undangan untuk info lengkapnya.";
        } else {
            $title = "{$weddingEmoji} Undangan Pernikahan {$coupleName}";
            $description = "🎉 Undangan Pernikahan {$coupleName}, {$eventDateFormatted}. Dengan sukacita kami mengundang Bapak/Ibu/Saudara/i untuk hadir memberikan doa restu.";
            $ogTitle = "{$weddingEmoji} Undangan Pernikahan {$coupleName}";
            $ogDescription = "🎊 Undangan Pernikahan {$coupleName}. {$eventDateFormatted}. Buka undangan untuk info lengkapnya.";
        }

        $canonicalUrl = $hasToParam ? url("/$path/invitation") : $currentUrl;
        $robotsMeta = $hasToParam ? 'noindex, follow' : 'index, follow';

        return [
            'title' => $title,
            'description' => $description,
            'og_title' => $ogTitle,
            'og_description' => $ogDescription,
            'og_image' => url('/assets/images/og-image.jpg'),
            'og_url' => $currentUrl,
            'canonical_url' => $canonicalUrl,
            'robots_meta' => $robotsMeta,
            'event_date_formatted' => $eventDateFormatted,
            'has_to_param' => $hasToParam,
            'guest_name' => $guestName,
            'location' => $location,
            'location_emoji' => $weddingEmoji,
            'event_key' => $eventKey
        ];
    }

    /**
     * Ucapan tamu dalam bentuk JSON.
     *
     * Dipakai daftar "Best Wishes" untuk mengambil sisa ucapan setelah 5
     * terbaru dirender dari server (`offset` + `limit`).
     */
    public function listMessages(Request $request)
    {
        $offset = max(0, (int) $request->get('offset', 0));
        $limit = (int) $request->get('limit', 5);
        $limit = max(1, min($limit, 50));

        $messages = Message::orderByDesc('created_at')
            ->orderByDesc('id')
            ->skip($offset)
            ->take($limit)
            ->get();

        return response()->json([
            'total' => Message::count(),
            'offset' => $offset,
            'messages' => $messages->map(fn (Message $message) => $this->messagePayload($message))->values(),
        ]);
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'guest_attends' => 'required|integer|min:1|max:10',
            'attendance' => 'required|string|in:Hadir,Tidak Hadir',
            'message' => 'nullable|string|max:500',
            'guest_code' => 'nullable|string',
            'guest_id' => 'nullable|integer',
        ]);

        // Update guest data jika ada guest_code
        if ($request->get('guest_code')) {
            Guest::where('code', $request->get('guest_code'))
                ->update([
                    'guest_attends' => $request->get('guest_attends'),
                    'is_opened' => true,
                    'attendance' => $request->get('attendance')
                ]);
        }

        // Create new message
        $newMessage = Message::create([
            'name' => $request->name,
            'guest_id' => $request->get('guest_id'),
            'message' => $request->message,
        ]);

        $messages = Message::orderByDesc('created_at')->orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'message' => 'Konfirmasi kehadiran berhasil dikirim!',
            'message_data' => $this->messagePayload($newMessage),
            'total' => $messages->count(),
            'messages' => $messages,
        ]);
    }

    /**
     * Bentuk data satu ucapan untuk dikirim ke halaman tamu / daftar ucapan.
     */
    private function messagePayload(Message $message): array
    {
        return [
            'id' => $message->id,
            'name' => $message->name,
            'message' => $message->message,
            'date' => optional($message->created_at)->locale('id')->translatedFormat('d M Y H:i'),
        ];
    }
}
