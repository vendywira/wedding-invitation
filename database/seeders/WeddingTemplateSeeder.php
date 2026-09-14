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

        WeddingTemplate::create([
            'name' => 'Vintage Romance',
            'slug' => 'vintage-romance',
            'description' => 'Template vintage dengan dekorasi bunga, tirai, lampu antik, partikel animasi, dan nuansa warm gold.',
            'thumbnail' => 'assets/vintage/floral-border.svg',
            'is_active' => false,
            'sections_config' => [
                'order' => ['modal', 'hero', 'couple', 'countdown', 'events', 'story', 'gallery', 'gift', 'rsvp', 'comments', 'footer'],
                'enabled' => [
                    'modal' => true,
                    'hero' => true,
                    'couple' => true,
                    'countdown' => true,
                    'events' => true,
                    'story' => true,
                    'gallery' => true,
                    'gift' => true,
                    'rsvp' => true,
                    'comments' => true,
                    'footer' => true,
                ],
            ],
            'styling_config' => [
                'primary_color' => '#FFF1D4',
                'secondary_color' => '#8B6914',
                'font_heading' => 'Cormorant Garamond',
                'font_body' => 'Catamaran',
                'font_script' => 'Monsieur La Doulaise',
                'animation_style' => 'zoom',
                'particles_enabled' => true,
                'particles_count' => 491,
                'decorative_elements' => true,
            ],
            'assets_config' => [
                'background_audio' => 'assets/audio/sound-bg.mp3',
                'hero_video' => null,
                'hero_image' => 'assets/vintage/bride.jpg',
                'bride_photo' => 'assets/vintage/bride.jpg',
                'groom_photo' => 'assets/vintage/groom.jpg',
                'logo_image' => 'assets/vintage/logo.png',
                'flower_decoration' => 'assets/vintage/flower.png',
                'curtain_decoration' => 'assets/vintage/curtain.png',
                'lamp_decoration' => 'assets/vintage/lamp.png',
                'divider_image' => 'assets/vintage/divider.png',
                'floral_border' => 'assets/vintage/floral-border.webp',
                'dresscode_image' => 'assets/vintage/dresscode.png',
                'story_image' => 'assets/vintage/story.jpg',
                'gallery_1' => 'assets/vintage/gallery-1.jpg',
                'gallery_2' => 'assets/vintage/gallery-2.jpg',
                'scroll_gif' => 'assets/vintage/scroll.gif',
                'lottie_animation' => 'assets/vintage/lottie.json',
                'bg_slide_1' => 'assets/vintage/bg-slide-1.jpg',
                'bg_slide_2' => 'assets/vintage/bg-slide-2.jpg',
            ],
        ]);
    }
}
