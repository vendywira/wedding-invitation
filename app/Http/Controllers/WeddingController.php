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
        $event->event_date_time_start = Carbon::parse($event->event_date . ' ' . $event->start_time)
            ->format('Y/m/d H:i:s');

        // Jika ada parameter guest (untuk route /p/{guest} atau /r/{guest})
        if ($guest) {
            $guestData = Guest::where('guest_code', $guest)->first();
        }

        // Jika tidak ada guest dari parameter, cek dari query string
        if (!$guestData && $request->has('to')) {
            $guestName =  urldecode($request->get('to'));

            // Cari guest berdasarkan nama
            $guestData = Guest::where('name', 'like', '%' . $guestName . '%')->first();

            if (!$guestData) {
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

        $messages = Message::orderBy('created_at', 'desc')->get();

        // Pass eventLocation ke view
        return view('wedding.invitation', compact('guestData', 'messages', 'event'));
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
