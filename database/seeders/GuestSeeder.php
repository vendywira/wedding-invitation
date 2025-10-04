<?php

namespace Database\Seeders;

use App\Models\Guest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GuestSeeder extends Seeder
{
    public function run()
    {
        // Kategori 1 - Resepsi Utama (Tabanan)
        $guestsCategory1 = [
            ['Keluarga Besar Arsa', 'utama'],
            ['Keluarga Besar Westri', 'utama'],
            ['Teman Kerja Yoga', 'utama'],
            ['Sahabat SMA Yoga', 'utama'],
        ];

        // Kategori 2 - Resepsi Kedua (Denpasar)
        $guestsCategory2 = [
            ['Keluarga Besar Sutila', 'denpasar'],
            ['Keluarga Besar Sumaniti', 'denpasar'],
            ['Teman Kuliah Rina', 'denpasar'],
            ['Komunitas Bali', 'denpasar'],
        ];

        foreach ($guestsCategory1 as $guest) {
            Guest::create([
                'name' => $guest[0],
                'guest_code' => Str::random(10),
                'category' => 'utama',
                'location' => 'Br. Dinas Belumbang Kaja, Tabanan',
                'max_guests' => 5,
                'is_opened' => false
            ]);
        }

        foreach ($guestsCategory2 as $guest) {
            Guest::create([
                'name' => $guest[0],
                'guest_code' => Str::random(10),
                'category' => 'denpasar',
                'location' => 'Jalan Merdeka No. 123, Denpasar',
                'max_guests' => 4,
                'is_opened' => false
            ]);
        }
    }
}
