<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'event_key' => 'rumah',
            'location' => 'GKPB Betesda sudimara',
            'event_date' => '2026-02-26',
            'start_time' => '14:00',
            'finish_time' => 'Selesai',
            'google_map_link' => 'https://maps.app.goo.gl/XVJMwHX1okeuGJ5Q7'
        ]);

        Event::create([
            'event_key' => 'gedung',
            'location' => 'Gedung serbaguna GKPB betesda sudimara',
            'event_date' => '2026-02-26',
            'start_time' => '18:00',
            'finish_time' => 'Selesai',
            'google_map_link' => 'https://maps.app.goo.gl/gMvgt3TUPqrMAqRT7'
        ]);
    }
}
