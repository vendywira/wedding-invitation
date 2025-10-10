<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WeddingController extends Controller
{
    public function show(Request $request, $guest = null)
    {
        $guestData = null;
        $path = $request->getPathInfo();
        $eventKey = str_starts_with($path, '/r/') ? 'rumah' : 'gedung';
        $event = Event::where('event_key', $eventKey)->first();

        if ($event) {
            $event->event_date_time_start = Carbon::parse($event->event_date . ' ' . $event->start_time)
                ->format('Y/m/d H:i:s');
        }

        if ($guest) {
            $guestData = Guest::where('guest_code', $guest)->first();
        }

        // Jika tidak ada guest dari parameter, cek dari query string
        if (!$guestData && $request->has('to')) {
            $guestName = urldecode($request->get('to'));

            // Cari guest berdasarkan nama
            $guestData = Guest::where('name', 'like', '%' . $guestName . '%')->first();

            if (!$guestData) {
                $guestData = new Guest([
                    'name' => $guestName,
                    'event_id' => $event ? $event->id : 1,
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

        $messages = Message::orderBy('created_at', 'desc')->get();
        $metaData = $this->generateMetaDataForBothRoutes($request, $event, $guestData, $eventKey);

        return response()
            ->view('wedding.invitation', compact('guestData', 'messages', 'event', 'metaData'))
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('X-Robots-Tag', $metaData['robots_meta']);
    }

    private function generateMetaDataForBothRoutes(Request $request, $event, $guestData, $eventKey)
    {
        $currentUrl = url()->current();
        $hasToParam = $request->has('to');
        $guestName = $guestData->name;
        $location = $event->location;
        $eventDateFormatted = $event ?
            Carbon::parse($event->event_date)->locale('id')->translatedFormat('l, j F Y') :
            'Rabu, 12 November 2025';
        $path = $eventKey === 'gedung' ? 'p' : 'r';
        $weddingEmoji = '🤵🏻💍👰🏻';

        if ($hasToParam && $guestName !== 'Tamu Undangan') {
            $title = "{$weddingEmoji} Undangan Untuk $guestName";
            $description = "$guestName, Anda diundang secara khusus! 🎉 Dalam pernikahan Vendy & Margareth, $eventDateFormatted. Konfirmasi kehadiran Anda!";
            $ogTitle = "{$weddingEmoji} Undangan Untuk {$guestName}";
            $ogDescription = "🎊 $guestName, Anda diundang! Dalam pernikahan Vendy & Margareth. $eventDateFormatted. Buka undangan untuk info lengkapnya.";
        } else {
            $title = "{$weddingEmoji} Undangan Pernikahan Vendy & Margareth";
            $description = "🎉 Undangan Pernikahan Vendy & Margareth, 12 November 2025. Dengan sukacita kami mengundang Bapak/Ibu/Saudara/i untuk hadir memberikan doa restu.";
            $ogTitle = "{$weddingEmoji} Undangan Pernikahan Vendy & Margareth";
            $ogDescription = "🎊 Undangan Pernikahan Vendy & Margareth. $eventDateFormatted. Buka undangan untuk info lengkapnya.";
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
        Message::create([
            'name' => $request->name,
            'guest_id' => $request->get('guest_id'),
            'message' => $request->message,
        ]);

        // Get updated messages for response
        $messages = Message::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Konfirmasi kehadiran berhasil dikirim!',
            'messages' => $messages
        ]);
    }
}
