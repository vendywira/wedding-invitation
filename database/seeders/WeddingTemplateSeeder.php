<?php

namespace Database\Seeders;

use App\Models\WeddingTemplate;
use Illuminate\Database\Seeder;

class WeddingTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Vintage Romance - the main template
        $vintage = WeddingTemplate::where('slug', 'vintage-romance')->first();
        if ($vintage) {
            $vintage->update([
                'template_settings' => [
                    'bride_name' => 'Isabel',
                    'groom_name' => 'Jefry',
                    'bride_full_name' => 'Isabela Sofia, S.T.',
                    'groom_full_name' => 'Jefry Alatas, S.T.',
                    'bride_father' => 'Abdul Kabir',
                    'bride_mother' => 'Masniawati',
                    'groom_father' => "Ba'dulu Alatas",
                    'groom_mother' => 'Halisah Herawati',
                    'bride_instagram' => '',
                    'groom_instagram' => '',
                    'couple_subtitle' => 'Bride & Groom',
                    'hero_subtitle' => 'a journey of love begins',
                    'hero_title' => 'THE WEDDING OF',
                    'hero_verse' => '"Di antara tanda-tanda (kebesaran)-Nya ialah bahwa Dia menciptakan pasangan-pasangan untukmu dari (jenis) dirimu sendiri agar kamu merasa tenteram kepadanya. Dia menjadikan di antaramu rasa cinta dan kasih sayang. Sesungguhnya pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir."',
                    'hero_verse_source' => '(Q.S Ar-rum 21)',
                    'story_title' => 'Our Story',
                    'story_subtitle' => 'Every love story is beautiful but ours is my favorite',
                    'gallery_title' => 'the Moments of',
                    'gallery_subtitle' => '',
                    'countdown_title' => 'Counting The Days',
                    'wedding_day_title' => 'Wedding Day',
                    'wedding_day_subtitle' => 'InsyaAllah akan dilaksanakan pada:',
                    'event_akad_title' => 'Akad Nikah',
                    'event_resepsi_title' => 'Resepsi Pernikahan',
                    'rsvp_title' => 'Konfirmasi Kehadiran',
                    'rsvp_subtitle' => 'Mohon konfirmasi kehadiran Bapak/Ibu/Saudara/i',
                    'gift_title' => 'Wedding Gift',
                    'gift_subtitle' => 'Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika Bapak/Ibu ingin memberikan tanda kasih, kami telah menyediakan fitur berikut:',
                    'thank_title' => 'Terima Kasih',
                    'thank_text' => 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Anda berkenan hadir dan memberikan doa restunya untuk pernikahan kami.',
                    'thank_closing' => 'Atas doa & restunya, kami ucapkan terima kasih.',
                    'footer_message' => 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kami.',
                    'wassalam_text' => "Wassalamu'alaikum Warahmatullahi Wabarakatuh",
                    'live_stream_url' => '',
                    // Section toggles used by the invitation template
                    'show_bride_photo' => '1',
                    'show_groom_photo' => '1',
                    'show_story_image' => '1',
                    'show_gallery' => '1',
                    'show_countdown' => '1',
                    'show_gift' => '1',
                ],
                // Text based assets (not uploads)
                'assets_config' => array_merge($vintage->assets_config ?? [], [
                    'bank_bni_number' => '557xxxx',
                    'bank_bni_name' => 'LINDA',
                    'bank_bri_number' => '00500xxx',
                    'bank_bri_name' => 'MUKHSIN',
                    'physical_gift_address' => 'Jl. Jaya Mangku, Kutai Kartanegara',
                    'physical_gift_name' => 'LINDA',
                ]),
                'gallery' => [],
            ]);

            $this->command->info('✅ Vintage Romance template settings seeded.');
        }
    }
}
