<?php

namespace Database\Seeders;

use App\Models\WeddingTemplate;
use Illuminate\Database\Seeder;

class WeddingTemplateSeeder extends Seeder
{
    public function run(): void
    {
        WeddingTemplate::updateOrCreate(
            ['slug' => 'vintage-romance'],
            [
                'name' => 'Vintage Romance',
                'description' => 'Template undangan pernikahan dengan nuansa vintage yang elegan.',
                'thumbnail' => 'assets/vintage/vendor/PAPER-BG-FLORAL-Q.jpg',
                'is_active' => true,
                'sections_config' => [
                    'enabled' => [
                        'hero' => true,
                        'couple' => true,
                        'story' => true,
                        'events' => true,
                        'gallery' => true,
                        'countdown' => true,
                        'gift' => true,
                        'rsvp' => true,
                        'live' => true,
                        'dresscode' => true,
                        'best_wishes' => true,
                        'thanks' => true,
                    ],
                    'order' => [
                        'hero',
                        'couple',
                        'story',
                        'events',
                        'gallery',
                        'countdown',
                        'gift',
                        'rsvp',
                        'live',
                        'dresscode',
                        'best_wishes',
                        'thanks',
                    ],
                ],
                'styling_config' => [
                    'primary_color' => '#8B7355',
                    'secondary_color' => '#D4C5A9',
                    'font_heading' => 'Caudex',
                    'font_body' => 'Catamaran',
                    'background_style' => 'floral',
                ],
                'assets_config' => [],
                'gallery' => [],
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
                    'show_bride_photo' => '1',
                    'show_groom_photo' => '1',
                    'show_story_image' => '1',
                    'show_gallery' => '1',
                    'show_countdown' => '1',
                    'show_gift' => '1',
                    'show_live' => '1',
                    'show_dresscode' => '1',
                    'show_story' => '1',
                    'show_best_wishes' => '1',
                    // SEO settings
                    'seo_meta_title' => '',
                    'seo_meta_description' => '',
                    'seo_og_title' => '',
                    'seo_og_description' => '',
                    'seo_og_image_asset' => 'cover_photo',
                ],
                'gifts' => [
                    [
                        'id' => 'gift-1',
                        'type' => 'bank',
                        'label' => 'Bank BNI',
                        'number' => '557xxxx',
                        'holder' => 'LINDA',
                        'address' => '',
                        'default_logo' => 'assets/vintage/vendor/bni.png',
                    ],
                    [
                        'id' => 'gift-2',
                        'type' => 'bank',
                        'label' => 'Bank BRI',
                        'number' => '00500xxx',
                        'holder' => 'MUKHSIN',
                        'address' => '',
                        'default_logo' => 'assets/vintage/vendor/Bank-Rakyat-Indonesia-BRI.png',
                    ],
                    [
                        'id' => 'gift-3',
                        'type' => 'address',
                        'label' => 'KIRIM KADO',
                        'number' => '',
                        'holder' => 'LINDA',
                        'address' => 'Jl. Jaya Mangku, Kutai Kartanegara',
                        'default_logo' => null,
                    ],
                ],
            ]
        );

        $this->command->info('✅ Vintage Romance template seeded.');
    }
}
