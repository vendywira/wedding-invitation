<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Message;
use Illuminate\Http\Request;

class WeddingController extends Controller
{
    public function show(Request $request, $guest = null)
    {
        $guestData = null;

        // Jika ada parameter guest (untuk route /p/{guest} atau /r/{guest})
        if ($guest) {
            $guestData = Guest::where('guest_code', $guest)->first();
        }

        // Jika tidak ada guest dari parameter, cek dari query string
        if (!$guestData && $request->has('to')) {
            $guestName = $request->get('to');

            // Cari guest berdasarkan nama
            $guestData = Guest::where('name', 'like', '%' . $guestName . '%')->first();

            if (!$guestData) {
                $guestData = new Guest([
                    'name' => $guestName,
                    'guest_code' => 'temp_' . uniqid(),
                    'max_guests' => 2
                ]);
            }
        }

        if (!$guestData) {
            $guestData = new Guest([
                'name' => 'Tamu Undangan',
                'guest_code' => 'default',
                'max_guests' => 2
            ]);
        }

        $messages = Message::orderBy('created_at', 'desc')->get();

        return view('wedding.invitation', compact('guestData', 'messages'));
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'guest_count' => 'required|integer|min:1|max:10',
            'attendance' => 'required|string|in:Hadir,Tidak Hadir',
            'message' => 'nullable|string|max:500',
            'guest_code' => 'nullable|string'
        ]);

        Message::create([
            'name' => $request->name,
            'guest_code' => $request->guest_code,
            'guest_count' => $request->guest_count,
            'attendance' => $request->attendance,
            'message' => $request->message,
            'category' => $request->guest_code ? 'personal' : 'umum',
            'location' => 'Br. Dinas Belumbang Kaja, Tabanan'
        ]);

        return response()->json(['success' => true]);
    }
}
