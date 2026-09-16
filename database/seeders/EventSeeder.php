<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Every row is one invitation group: `event_key` is its public URL slug
     * (/p/invitation, /r/invitation) and `group_name` is its dashboard label.
     * More groups can be added from the dashboard (Settings → Acara).
     */
    public function run(): void
    {
        // `truncate()` cannot be used here: `events` is referenced by the
        // guests and event_details foreign keys.
        EventDetail::query()->delete();
        Event::query()->delete();

        Event::create([
            'event_key' => 'p',
            'group_name' => 'Resepsi (Gedung)',
            'is_default' => true,
            'sort_order' => 0,
            'title' => 'Resepsi Pernikahan',
            'location' => 'Wedding Venue Taman Prakerti Bhuana Indraprasta Ballroom',
            'event_date' => '2025-11-12',
            'start_time' => '17:00',
            'finish_time' => '21:00 WITA',
            'google_map_link' => 'https://maps.app.goo.gl/gMvgt3TUPqrMAqRT7',
        ]);

        Event::create([
            'event_key' => 'r',
            'group_name' => 'Akad / Rumah',
            'is_default' => false,
            'sort_order' => 1,
            'title' => 'Akad Nikah',
            'location' => 'Br. Dinas Sudimara Kaja, Ds. Sudimara, Kec. Tabanan, Kab. Tabanan',
            'event_date' => '2025-11-15',
            'start_time' => '08:00',
            'finish_time' => 'Selesai',
            'google_map_link' => 'https://maps.app.goo.gl/XVJMwHX1okeuGJ5Q7',
        ]);
    }
}
