<?php

namespace Database\Seeders;

use App\Models\WeddingTemplate;
use Illuminate\Database\Seeder;

class WeddingTemplateSeeder extends Seeder
{
    public function run(): void
    {
        WeddingTemplate::create([
            'name' => 'Elegant Floral',
            'slug' => 'elegant-floral',
            'description' => 'Template klasik dengan desain floral elegan, carousel foto, dan animasi fade-in.',
            'thumbnail' => 'assets/images/gallery/slide1.jpg',
            'is_active' => true,
            'sections_config' => [
                'order' => ['hero', 'quote', 'pengantar', 'couple', 'events', 'gallery', 'gift', 'rsvp', 'comments', 'footer'],
                'enabled' => [
                    'hero' => true,
                    'quote' => true,
                    'pengantar' => true,
                    'couple' => true,
                    'events' => true,
                    'gallery' => true,
                    'gift' => true,
                    'rsvp' => true,
                    'comments' => true,
                    'footer' => true,
                ],
            ],
            'styling_config' => [
                'primary_color' => '#e44d26',
                'secondary_color' => '#f26161',
                'font_heading' => 'default',
                'font_body' => 'default',
                'animation_style' => 'fade',
            ],
            'assets_config' => [
                'background_audio' => 'assets/audio/sound-bg.mp3',
                'hero_video' => 'assets/videos/wedding-bg-2.mp4',
                'hero_image' => 'assets/images/gallery/gal-2.jpg',
            ],
        ]);
    }
}
