<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Event::create([
            'event_key' => 'rumah',
            'location' => 'Br. Dinas Sudimara Kaja, Ds. Sudimara, Kec. Tabanan, Kab. Tabanan',
            'event_date' => '2025-11-15',
            'start_time' => '08:00',
            'finish_time' => 'Selesai',
            'google_map_link' => 'https://maps.app.goo.gl/XVJMwHX1okeuGJ5Q7'
        ]);

        Event::create([
            'event_key' => 'gedung',
            'location' => 'Wedding Venue Taman Prakerti Bhuana Indraprasta Ballroom',
            'event_date' => '2025-11-12',
            'start_time' => '17:00',
            'finish_time' => '21:00 WITA',
            'google_map_link' => 'https://maps.app.goo.gl/gMvgt3TUPqrMAqRT7'
        ]);
    }
}
