<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MessageTemplate;

class MessageTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Template Formal',
                'template' => '*UNDANGAN PERNIKAHAN*

Kepada Yth. {guest_name}

Dengan penuh sukacita, kami mengundang Bapak/Ibu/Saudara/i untuk hadir dalam acara pernikahan kami:

*{groom_name} & {bride_name}*
📅 *{event_date}*
⏰ *{event_time}*
📌 *{event_location}*

Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.

Bantu kami mempersiapkan tempat terbaik untuk anda dengan konfirmasi kehadiran: {invitation_link}

*Terima kasih,*
*{groom_name} & {bride_name}*',
                'is_active' => true,
                'is_default' => true
            ],
            [
                'name' => 'Template Casual',
                'template' => 'Hai {guest_name}! 🎉

Kami mau ngasih kabar baik nih!
Kami akan menikah dan sangat berharap kamu bisa hadir di hari bahagia kami:

✨ *{groom_name} & {bride_name}* ✨
🗓️ {event_date}
⏰ {event_time}
📍 {event_location}

Bantu kami persiapkan semuanya dengan konfirmasi kehadiran kamu di:
{invitation_link}

Cant wait to see you! 💕',
                'is_active' => true,
                'is_default' => false
            ],
            [
                'name' => 'Template Simple',
                'template' => 'Undangan Pernikahan
{guest_name}

{groom_name} & {bride_name}

📅 {event_date}
⏰ {event_time}
📍 {event_location}

Link konfirmasi: {invitation_link}

Terima kasih',
                'is_active' => true,
                'is_default' => false
            ]
        ];

        foreach ($templates as $template) {
            MessageTemplate::create($template);
        }
    }
}
