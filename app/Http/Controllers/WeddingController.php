<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Message;
use Illuminate\Http\Request;

class WeddingController extends Controller
{
    public function show($guest_code = null)
    {
        $guest = null;

        if ($guest_code) {
            $guest = Guest::where('guest_code', $guest_code)->first();

            if ($guest && !$guest->is_opened) {
                $guest->update(['is_opened' => true]);
            }
        }

        $messages = Message::orderBy('created_at', 'desc')->get();

        $namaTamu = request()->get('to');
        if ($namaTamu && !$guest) {
            $guest = (object) ['name' => $namaTamu];
        }

        return view('wedding.invitation', compact('guest', 'messages'));
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
