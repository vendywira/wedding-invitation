# Wedding Template System Implementation Plan

**For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Convert existing monolithic invitation into a template-based system where admins can switch between completely different wedding invitation UIs.

**Architecture:** Each template is a folder with its own Blade views, CSS, and JS. A `wedding_templates` table stores template metadata and configuration. Controller reads active template from DB and renders the correct view folder. All templates consume the same data contract (Event, Guest, Message).

**Tech Stack:** Laravel 12, Blade templates, SQLite, Bootstrap 5, jQuery

---

## File Structure

```
resources/views/wedding/
├── templates/
│   ├── elegant-floral/              ← Current template (migrated)
│   │   ├── index.blade.php
│   │   ├── sections/
│   │   │   ├── hero.blade.php
│   │   │   ├── couple.blade.php
│   │   │   ├── events.blade.php
│   │   │   ├── gallery.blade.php
│   │   │   ├── gift.blade.php
│   │   │   ├── rsvp.blade.php
│   │   │   └── footer.blade.php
│   │   ├── css/
│   │   │   └── template.css
│   │   └── js/
│   │       └── template.js

database/
├── migrations/
│   └── 2026_09_13_000001_create_wedding_templates_table.php

app/
├── Models/
│   └── WeddingTemplate.php

app/Http/Controllers/
├── WeddingTemplateController.php

routes/
├── web.php (modified)
```

---

## Task 1: Create Migration & Model

**Files:**
- Create: `database/migrations/2026_09_13_000001_create_wedding_templates_table.php`
- Create: `app/Models/WeddingTemplate.php`

- [ ] **Step 1: Create migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('sections_config')->nullable();
            $table->json('styling_config')->nullable();
            $table->json('assets_config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_templates');
    }
};
```

- [ ] **Step 2: Create WeddingTemplate model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeddingTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'is_active',
        'sections_config',
        'styling_config',
        'assets_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sections_config' => 'array',
        'styling_config' => 'array',
        'assets_config' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->is_active) {
                static::where('id', '!=', $model->id)->update(['is_active' => false]);
            }
        });
    }

    public function getSectionsAttribute(): array
    {
        return $this->sections_config['order'] ?? [];
    }

    public function isSectionEnabled(string $section): bool
    {
        return $this->sections_config['enabled'][$section] ?? true;
    }

    public function getAsset(string $key, ?string $default = null): ?string
    {
        return $this->assets_config[$key] ?? $default;
    }

    public function getStyle(string $key, ?string $default = null): ?string
    {
        return $this->styling_config[$key] ?? $default;
    }
}
```

- [ ] **Step 3: Run migration**

```bash
php artisan migrate
```

- [ ] **Step 4: Commit**

```bash
git add database/migrations/2026_09_13_000001_create_wedding_templates_table.php app/Models/WeddingTemplate.php
git commit -m "feat: add wedding_templates table and model"
```

---

## Task 2: Seed Default Template from Current Invitation

**Files:**
- Create: `database/seeders/WeddingTemplateSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 1: Create seeder**

```php
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
```

- [ ] **Step 2: Update DatabaseSeeder**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WeddingTemplateSeeder::class,
        ]);
    }
}
```

- [ ] **Step 3: Run seeder**

```bash
php artisan db:seed --class=WeddingTemplateSeeder
```

- [ ] **Step 4: Commit**

```bash
git add database/seeders/WeddingTemplateSeeder.php database/seeders/DatabaseSeeder.php
git commit -m "feat: seed default elegant-floral template"
```

---

## Task 3: Create Template Folder Structure

**Files:**
- Create: `resources/views/wedding/templates/elegant-floral/index.blade.php`
- Create: `resources/views/wedding/templates/elegant-floral/sections/hero.blade.php`
- Create: `resources/views/wedding/templates/elegant-floral/sections/couple.blade.php`
- Create: `resources/views/wedding/templates/elegant-floral/sections/events.blade.php`
- Create: `resources/views/wedding/templates/elegant-floral/sections/gallery.blade.php`
- Create: `resources/views/wedding/templates/elegant-floral/sections/gift.blade.php`
- Create: `resources/views/wedding/templates/elegant-floral/sections/rsvp.blade.php`
- Create: `resources/views/wedding/templates/elegant-floral/sections/comments.blade.php`
- Create: `resources/views/wedding/templates/elegant-floral/sections/footer.blade.php`
- Create: `resources/views/wedding/templates/elegant-floral/css/template.css`
- Create: `resources/views/wedding/templates/elegant-floral/js/template.js`

- [ ] **Step 1: Create index.blade.php (main layout)**

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $metaData['title'] }}</title>
    <meta name="description" content="{{ $metaData['description'] }}">
    <meta name="robots" content="{{ $metaData['robots_meta'] }}">
    <meta property="og:title" content="{{ $metaData['og_title'] }}">
    <meta property="og:description" content="{{ $metaData['og_description'] }}">
    <meta property="og:image" content="{{ $metaData['og_image'] }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="canonical" href="{{ $metaData['canonical_url'] }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/popup/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ $template->slug }}/css/template.css">
    <script src="https://kit.fontawesome.com/700f98b672.js" crossorigin="anonymous"></script>
</head>
<body data-aos-easing="ease" data-aos-duration="1500" data-aos-delay="500">
    <audio id="myAudio" loop muted autoplay>
        <source src="{{ asset($template->getAsset('background_audio', 'assets/audio/sound-bg.mp3')) }}" type="audio/mp3">
    </audio>

    <div id="btn-audio">
        <button id="click-btn" onclick="Play()">
            <img src="{{ asset('assets/images/pause.svg') }}" id="playPausebtn">
        </button>
    </div>

    @if($template->isSectionEnabled('hero'))
        @include('wedding.templates.elegant-floral.sections.hero')
    @endif

    @if($template->isSectionEnabled('quote'))
        @include('wedding.templates.elegant-floral.sections.quote')
    @endif

    @if($template->isSectionEnabled('pengantar'))
        @include('wedding.templates.elegant-floral.sections.pengantar')
    @endif

    @if($template->isSectionEnabled('couple'))
        @include('wedding.templates.elegant-floral.sections.couple')
    @endif

    @if($template->isSectionEnabled('events'))
        @include('wedding.templates.elegant-floral.sections.events')
    @endif

    @if($template->isSectionEnabled('gallery'))
        @include('wedding.templates.elegant-floral.sections.gallery')
    @endif

    @if($template->isSectionEnabled('gift'))
        @include('wedding.templates.elegant-floral.sections.gift')
    @endif

    @if($template->isSectionEnabled('rsvp'))
        @include('wedding.templates.elegant-floral.sections.rsvp')
    @endif

    @if($template->isSectionEnabled('comments'))
        @include('wedding.templates.elegant-floral.sections.comments')
    @endif

    @if($template->isSectionEnabled('footer'))
        @include('wedding.templates.elegant-floral.sections.footer')
    @endif

    <script src="{{ asset('assets/scripts/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/aos.js') }}"></script>
    <script src="{{ asset('assets/scripts/multi-countdown.js') }}"></script>
    <script src="{{ asset('assets/scripts/konfirmasi.js') }}"></script>
    <script src="{{ asset('assets/popup/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ $template->slug }}/js/template.js"></script>
</body>
</html>
```

- [ ] **Step 2: Create sections/hero.blade.php**

```blade
<!-- Popup Modal -->
@if(isset($guestData) && $guestData)
<div id="modal" style="opacity: 1; top: 0;">
    <section class="popup">
        <div class="video-container">
            <video autoplay muted loop playsinline id="popupVideo" class="video-background" preload="auto" webkit-playsinline>
                <source src="{{ asset($template->getAsset('hero_video', 'assets/videos/wedding-bg-2.mp4')) }}" type="video/mp4">
                <img src="{{ asset($template->getAsset('hero_image', 'assets/images/gallery/gal-2.jpg')) }}" alt="Wedding Background" class="fallback-image">
            </video>
        </div>
        <div class="popup-content" style="margin-top: -60px;">
            <h1>THE WEDDING OF</h1>
            <h2>Vendy & Margareth</h2>
            <span class="h3"><strong>#loVENDYngnyaMARGARETH</strong></span>
            <span class="h3">{{ Carbon\Carbon::parse($event->event_date)->format('d / m / y') }}</span>
            <br><br><br><br><br><br><br><br><br><br><br><br>
            <div class="yth">
                Kepada Yth Bapak/Ibu/Saudara/i:<br>
                <div class="nama-tamu">{{ $guestData->name ?? request()->get('to', 'Tamu Undangan') }}</div>
                <button id="hidePlay" onclick="hidePlay()">
                    <i class="fas fa-envelope-open-text" aria-hidden="true"></i> Buka Undangan
                </button>
            </div>
        </div>
    </section>
</div>
@endif

<!-- Carousel Section -->
<div id="carousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="3000" data-pause="false">
    <div class="carousel-inner carousel-zoom">
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide1.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide2.jpg') }}"></div>
        <div class="item active"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide3.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide4.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide5.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide6.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide7.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide8.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide9.jpg') }}"></div>
        <div class="gradient"></div>
        <div id="header">
            <h1>PERNIKAHAN</h1>
            <h2>Vendy & Margareth</h2>
            <h3><strong>#loVENDYngnyaMARGARETH</strong></h3>
            <h3>{{ Carbon\Carbon::parse($event->event_date)->format('d / m / y') }}</h3>
        </div>
        <svg class="bg-wave1" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 500 100" xml:space="preserve">
            <style type="text/css">.st0 { opacity: .5 }</style>
            <path fill="#fff" transform="rotate(90deg)" class="st0" d="M0,0.1c0,0,37-3.5,221,34.4s279,17.6,279,17.6v20.3H0V0.1z"></path>
            <path fill="#fff" transform="rotate(90deg)" class="st0" d="M500,0.1c0,0-37-3.5-221,34.4S0,52.1,0,52.1l0,20.3h500V0.1z"></path>
            <path fill="#fff" transform="rotate(90deg)" class="st0" d="M0,16.4c0,0,37-2.7,221,26.6s279,13.6,279,13.6v15.7H0V16.4z"></path>
            <path fill="#fff" transform="rotate(90deg)" class="st0" d="M500,16.4c0,0-37-2.7-221,26.6S0,56.6,0,56.6l0,15.7h500V16.4z"></path>
            <path fill="#fff" transform="rotate(90deg)" class="st0" d="M0,35.3c0,0,37-1.8,221,17.6s279,9,279,9v10.4H0V35.3z"></path>
            <path fill="#fff" transform="rotate(90deg)" class="st0" d="M500,35.3c0,0-37-1.8-221,17.6s-279,9-279,9l0,10.4h500V35.3z"></path>
        </svg>
    </div>
</div>
```

- [ ] **Step 3: Create sections/couple.blade.php**

```blade
<section id="tentang-pasangan">
    <div class="container text-center">
        <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
        <div class="tittle-section" data-aos="zoom-in-down">Sang Mempelai</div>
        <div class="row">
            <div class="col-lg-6">
                <div class="flip-container" data-aos="zoom-in">
                    <div class="flipper" id="flipper-groom">
                        <div class="front">
                            <div class="image-wrapper">
                                <img src="{{ asset('assets/images/gallery/groom.jpg') }}" class="profile-image">
                            </div>
                        </div>
                        <div class="back">
                            <div class="image-wrapper">
                                <img src="{{ asset('assets/images/gallery/groom3.jpg') }}" class="profile-image">
                            </div>
                        </div>
                    </div>
                </div>
                <h2 data-aos="flip-left">I Wayan Vendy Wiranatha S.Kom</h2>
                <div data-aos="fade-up">
                    <p>Putra pertama dari pasangan<br>
                        Bapak drh. I Made Sunastra dengan Ibu Triwik Susanti<br><br>
                        <i class="fa fa-map-marker-alt" aria-hidden="true"></i> Br. Dinas Sudimara Kaja, Ds. Sudimara, Kec. Tabanan, Kab. Tabanan</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="flip-container" data-aos="zoom-in">
                    <div class="flipper" id="flipper-bride">
                        <div class="front">
                            <div class="image-wrapper">
                                <img src="{{ asset('assets/images/gallery/bride.jpg') }}" class="profile-image">
                            </div>
                        </div>
                        <div class="back">
                            <div class="image-wrapper">
                                <img src="{{ asset('assets/images/gallery/bride3.jpg') }}" class="profile-image">
                            </div>
                        </div>
                    </div>
                </div>
                <h2 data-aos="flip-left">Margaretha Magdalena Br. Nainggolan</h2>
                <div data-aos="fade-up">
                    <p>Putri kedua dari pasangan<br>
                        Bapak Hery Nainggolan dengan Ibu Timak Br Sianturi<br><br>
                        <i class="fa fa-map-marker-alt" aria-hidden="true"></i> Cipayung, Jakarta Timur, DKI Jakarta</p>
                </div>
            </div>
        </div>
    </div>
</section>
```

- [ ] **Step 4: Create sections/events.blade.php**

```blade
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Info Acara</div>

<section id="acara">
    <div class="container text-center box">
        <div class="row">
            <div class="col-lg-12">
                <div data-aos="zoom-in-down">
                    <p>
                        <i class="far fa-map" aria-hidden="true"></i> {{ $event->location }}<br>
                        <i class="far fa-calendar-check" aria-hidden="true"></i> {{ (new \IntlDateFormatter('id_ID', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, 'Asia/Jakarta', \IntlDateFormatter::GREGORIAN, 'EEEE, d MMMM y'))->format(new DateTime($event->event_date)) }}<br>
                        <i class="far fa-clock" aria-hidden="true"></i> {{ $event->start_time }} - {{ $event->finish_time }}<br>
                    </p>
                </div>
                <div data-aos="fade-up">
                    <a href="{{ $event->google_map_link }}" target="_blank" class="btn-map">
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i> Google Map
                    </a>
                    <a href="javascript:void(0)" class="btn-map btn-save-date" onclick="smartAddToCalendar()" style="margin-left: 15px">
                        <i class="far fa-calendar-plus" aria-hidden="true"></i> Save the Date
                    </a>
                </div>
                <br>
                <div class="countdown show" data-aos="zoom-in" data-date="{{ $event->event_date_time_start }}" style="display: block;">
                    <div class="text"><h2>Waktu Menuju Acara</h2></div>
                    <div class="running" style="display: flex;">
                        <timer>
                            <span class="days">07</span>:<span class="hours">13</span>:<span class="minutes">02</span>:<span class="seconds">16</span>
                        </timer>
                    </div>
                    <div class="labels">
                        <span class="label-days">Hari</span>
                        <span class="label-hours">Jam</span>
                        <span class="label-minutes">Menit</span>
                        <span class="label-seconds">Detik</span>
                    </div>
                    <div class="break"></div>
                </div>
            </div>
        </div>
    </div>
</section>
```

- [ ] **Step 5: Create sections/gallery.blade.php**

```blade
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Photo Gallery</div>

<div class="container">
    <div class="masonry">
        @for($i = 1; $i <= 14; $i++)
        <div class="galley" data-aos="zoom-in">
            <a class="image-popup" href="{{ asset('assets/images/gallery/gal-' . $i . '.jpg') }}">
                <img src="{{ asset('assets/images/gallery/gal-' . $i . '.jpg') }}" class="img-responsive border">
            </a>
        </div>
        @endfor
    </div>
</div>
```

- [ ] **Step 6: Create sections/gift.blade.php**

```blade
<br><br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Kado Digital</div>

<section id="gift" class="gift-section">
    <div class="gift-container">
        <p class="gift-subtitle" data-aos="zoom-in">
            Bagi keluarga dan sahabat yang ingin mengirimkan hadiah secara cashless,
            silahkan transfer ke salah satu rekening berikut:
        </p>
        <div class="gift-cards">
            <div class="gift-card" data-aos="fade-up" data-aos-delay="100">
                <div class="gift-card-header">
                    <img src="{{ asset('assets/images/bca.png') }}" alt="BCA" class="gift-logo">
                    <h3 class="gift-bank-name">Bank BCA</h3>
                </div>
                <div class="gift-details">
                    <p class="gift-account-name">Nomor Rekening:</p>
                    <div class="gift-account-number" id="bca-account">1420421377</div>
                    <p class="gift-account-name">Atas Nama:</p>
                    <p class="gift-account-holder">I Wayan Vendy Wiranatha</p>
                </div>
                <div class="gift-actions">
                    <button class="gift-copy-btn" onclick="copyToClipboard('bca-account')">
                        <i class="fas fa-copy"></i> Salin Nomor
                    </button>
                </div>
            </div>
            <div class="gift-card" data-aos="fade-up" data-aos-delay="200">
                <div class="gift-card-header">
                    <img src="{{ asset('assets/images/jago.jpg') }}" alt="Jago" class="gift-logo">
                    <h3 class="gift-bank-name">Bank Jago</h3>
                </div>
                <div class="gift-details">
                    <p class="gift-account-name">Nomor Rekening:</p>
                    <div class="gift-account-number" id="jago-account">109246172960</div>
                    <p class="gift-account-name">Atas Nama:</p>
                    <p class="gift-account-holder">I Wayan Vendy Wiranatha</p>
                </div>
                <div class="gift-actions">
                    <button class="gift-copy-btn" onclick="copyToClipboard('jago-account')">
                        <i class="fas fa-copy"></i> Salin Nomor
                    </button>
                </div>
            </div>
            <div class="gift-card" data-aos="fade-up" data-aos-delay="300">
                <div class="gift-card-header">
                    <img src="{{ asset('assets/images/bri.png') }}" alt="BRI" class="gift-logo">
                    <h3 class="gift-bank-name">Bank BRI</h3>
                </div>
                <div class="gift-details">
                    <p class="gift-account-name">Nomor Rekening:</p>
                    <div class="gift-account-number" id="bri-account">012401062555500</div>
                    <p class="gift-account-name">Atas Nama:</p>
                    <p class="gift-account-holder">Margaretha Magdalena</p>
                </div>
                <div class="gift-actions">
                    <button class="gift-copy-btn" onclick="copyToClipboard('bri-account')">
                        <i class="fas fa-copy"></i> Salin Nomor
                    </button>
                </div>
            </div>
        </div>
        <p class="gift-note" data-aos="zoom-in">
            <i class="fas fa-info-circle"></i>
            Kehadiran dan doa Bapak/Ibu/Saudara/i sudah merupakan kebahagiaan tersendiri bagi kami.
            Apabila berkenan memberikan tanda kasih, kami dapat menerima konfirmasi
            melalui WhatsApp atau form kehadiran.
            Terima kasih atas cinta dan restunya.
        </p>
    </div>
</section>
```

- [ ] **Step 7: Create sections/rsvp.blade.php**

```blade
<br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Konfirmasi Tamu</div>

<section id="konfirmasi" data-aos="zoom-in">
    <div class="konfirmasi-container">
        <div class="konfirmasi-wrapper">
            <div class="konfirmasi-box">
                <form id="konfirmasi-form" method="POST" autocomplete="off" class="form-group">
                    @csrf
                    <input type="hidden" name="guest_code" value="{{ $guestData->code }}">
                    <input type="hidden" name="guest_id" value="{{ $guestData->id }}">
                    <div class="text-center mb-4">
                        <h3 class="form-title">Konfirmasi Kehadiran</h3>
                        <p class="form-subtitle">Dimohon kesediaannya untuk mengisi form kehadiran undangan kami</p>
                    </div>
                    <div class="form-group-row">
                        <div class="col-12">
                            <input type="text" class="form-control custom-input" name="name" id="nama-fm" placeholder="Nama Lengkap" required value="{{ $guestData->name ?? request()->get('to') }}">
                        </div>
                    </div>
                    <div class="form-group-row">
                        <div class="col-12">
                            <select class="form-control custom-select" name="guest_attends" id="jumlah-fm" required>
                                <option value="" disabled selected>Pilih Jumlah Tamu</option>
                                <option value="1">1 Orang</option>
                                <option value="2">2 Orang</option>
                                <option value="3">3 Orang</option>
                                <option value="4">4 Orang</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group-row">
                        <div class="col-12">
                            <select class="form-control custom-select" name="attendance" id="kehadiran-fm" required>
                                <option value="" disabled selected>Konfirmasi Kehadiran</option>
                                <option value="Hadir">Iya, Saya Hadir</option>
                                <option value="Tidak Hadir">Maaf, Saya Tidak Hadir</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group-row">
                        <div class="col-12">
                            <textarea class="form-control custom-textarea" maxlength="250" name="message" id="pesan-fm" rows="3" placeholder="Ketikan Ucapan / Doa untuk mempelai"></textarea>
                            <div class="char-counter">
                                <span id="char-count">0</span>/250 karakter
                            </div>
                        </div>
                    </div>
                    <div class="form-group-row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-submit" name="send-konfirmasi" id="send-konfirmasi">
                                <span class="btn-text">KIRIM KONFIRMASI</span>
                                <div class="btn-loading d-none">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Mengirim...
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
```

- [ ] **Step 8: Create sections/comments.blade.php**

```blade
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Kiriman Ucapan</div>

<section id="komentar">
    <div class="container komentar-box">
        <div class="container-fluid">
            <div class="col-xs-8 col-sm-6" id="totalDoa-view" data-aos="zoom-in">
                <strong>{{ $messages->count() }} Ucapan</strong>
            </div>
        </div>
        <br>
        <div class="container-fluid comments-scrollable-container" data-aos="zoom-in">
            <div class="comments-scrollable @if($messages->count() <= 5) no-scroll @endif" id="listkomentar" @if($messages->count() > 5) style="max-height: 400px; overflow-y: auto;" @endif>
                @if($messages->count() > 0)
                @foreach($messages as $message)
                <div class="comment-item">
                    <strong>{{ $message->name }}</strong>
                    <small>{{ $message->created_at->format('d M Y H:i') }}</small>
                    <p>{{ $message->message }}</p>
                </div>
                @endforeach
                @else
                <div class="text-center no-comments">
                    <p>Belum ada ucapan. Jadilah yang pertama mengucapkan selamat!</p>
                </div>
                @endif
            </div>
            @if($messages->count() > 5)
            <div class="scroll-indicator" id="scrollIndicator">
                <i class="fas fa-chevron-down"></i>
                <span>Scroll untuk melihat lebih banyak ucapan</span>
            </div>
            @endif
        </div>
        <div align="center" data-aos="zoom-in-down">
            <button type="button" id="showallcomment-btn" class="btn @if($messages->count() <= 5) invisible @endif" name="submit">
                TAMPILKAN SEMUA ({{ $messages->count() }})
            </button>
        </div>
    </div>
</section>
```

- [ ] **Step 9: Create sections/footer.blade.php**

```blade
<section id="penutup" class="container text-center" data-aos="zoom-in-down">
    Merupakan suatu kehormatan dan kebahagiaan kami, apabila Bapak/Ibu/Saudara/i berkenan hadir memberikan doa restu<br>
</section>

<section id="footer-wave">
    <section id="footer-top"></section>
    <svg class="bg-wave2" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 500 75" xml:space="preserve">
        <style type="text/css">.st0 { opacity: .4 }</style>
        <path fill="#000" transform="rotate(90deg)" class="st0" d="M0,0.1c0,0,37-3.5,221,34.4s279,17.6,279,17.6v20.3H0V0.1z"></path>
        <path fill="#000" transform="rotate(90deg)" class="st0" d="M500,0.1c0,0-37-3.5-221,34.4S0,52.1,0,52.1l0,20.3h500V0.1z"></path>
        <path fill="#000" transform="rotate(90deg)" class="st0" d="M0,16.4c0,0,37-2.7,221,26.6s279,13.6,279,13.6v15.7H0V16.4z"></path>
        <path fill="#000" transform="rotate(90deg)" class="st0" d="M500,16.4c0,0-37-2.7-221,26.6S0,56.6,0,56.6l0,15.7h500V16.4z"></path>
        <path fill="#000" transform="rotate(90deg)" class="st0" d="M0,35.3c0,0,37-1.8,221,17.6s279,9,279,9v10.4H0V35.3z"></path>
        <path fill="#000" transform="rotate(90deg)" class="st0" d="M500,35.3c0,0-37-1.8-221,17.6s-279,9-279,9l0,10.4h500V35.3z"></path>
    </svg>
</section>

<section id="footer" class="container">
    <div class="col-sm-12 text-center">
        <h1 data-aos="fade-down">Kami Yang Berbahagia</h1>
        <h2 data-aos="fade-up">Vendy & Margareth</h2>
        <h6 data-aos="fade-up">#loVENDYngnyaMARGARETH</h6>
        <h3>© 2025 Wedding Invitation by <a href="https://linkedin.com/in/vendywira">I Wayan Vendy Wiranatha</a></h3>
        <div class="col-md-12 col-sm-12 social">
            <a href="https://www.facebook.com/vendy.wiranatha" target="_blank">
                <i class="fab fa-1x fa-facebook-f" aria-hidden="true"></i>
            </a>
            <a href="https://www.instagram.com/vendywira/" target="_blank">
                <i class="fab fa-1x fa-instagram" aria-hidden="true"></i>
            </a>
            <a href="https://linkedin.com/in/vendywira" target="_blank">
                <i class="fab fa-1x fa-linkedin" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</section>

<!-- Success Modal -->
<div id="successModal" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="padding:20px; border-radius:15px;">
            <div class="row">
                <div class="col-md-12 p-4 text-center">
                    <img src="{{ asset('assets/images/check.svg') }}" width="50px"><br>
                    <h5 style="margin-top:15px; color:#28a745;">Berhasil!</h5>
                </div>
                <div class="col-md-12 p-3 text-center">
                    <p style="margin-bottom:20px;">Konfirmasi kehadiran dan ucapan Anda berhasil dikirim</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="copyToast" class="copy-toast">
    <i class="fas fa-check-circle"></i>
    <span class="toast-message">Nomor rekening berhasil disalin!</span>
</div>
```

- [ ] **Step 10: Create CSS file**

Copy existing `style3.css` content to `resources/views/wedding/templates/elegant-floral/css/template.css`.

- [ ] **Step 11: Create JS file**

Create `resources/views/wedding/templates/elegant-floral/js/template.js` with audio, countdown, flip, copy, and calendar functions extracted from current invitation.

- [ ] **Step 12: Commit**

```bash
git add resources/views/wedding/templates/
git commit -m "feat: create elegant-floral template structure"
```

---

## Task 4: Update Controller to Use Template System

**Files:**
- Modify: `app/Http/Controllers/WeddingController.php`

- [ ] **Step 1: Update show() method**

```php
public function show(Request $request, $guest = null)
{
    $template = \App\Models\WeddingTemplate::where('is_active', true)->first();
    
    if (!$template) {
        abort(404, 'No active template found');
    }

    $guestData = null;
    $path = $request->getPathInfo();
    $eventKey = str_starts_with($path, '/r/') ? 'rumah' : 'gedung';
    $event = Event::where('event_key', $eventKey)->first();

    if ($event) {
        $event->event_date_time_start = Carbon::parse($event->event_date . ' ' . $event->start_time)
            ->format('Y/m/d H:i:s');
    }

    if ($guest) {
        $guestData = Guest::where('guest_code', $guest)->first();
    }

    if (!$guestData && $request->has('to')) {
        $guestName = urldecode($request->get('to'));
        $guestData = Guest::where('name', 'like', '%' . $guestName . '%')->first();

        if (!$guestData) {
            $guestData = new Guest([
                'name' => $guestName,
                'event_id' => $event ? $event->id : 1,
                'code' => uniqid(),
                'guest_attends' => 1,
                'is_opened' => true
            ]);
            $guestData->save();
        }
    }

    if (!$guestData) {
        $guestData = new Guest([
            'name' => 'Tamu Undangan',
            'code' => uniqid(),
            'guest_attends' => 1,
        ]);
    }

    $messages = Message::orderBy('created_at', 'desc')->get();
    $metaData = $this->generateMetaDataForBothRoutes($request, $event, $guestData, $eventKey);

    $viewPath = "wedding.templates.{$template->slug}.index";

    return response()
        ->view($viewPath, compact('guestData', 'messages', 'event', 'metaData', 'template'))
        ->header('Content-Type', 'text/html; charset=utf-8')
        ->header('X-Robots-Tag', $metaData['robots_meta']);
}
```

- [ ] **Step 2: Test current invitation still works**

Visit `/invitation` and verify the page renders correctly with the template system.

- [ ] **Step 3: Commit**

```bash
git add app/Http/Controllers/WeddingController.php
git commit -m "feat: update controller to use template system"
```

---

## Task 5: Create Template Controller for Admin

**Files:**
- Create: `app/Http/Controllers/WeddingTemplateController.php`
- Modify: `routes/web.php`

- [ ] **Step 1: Create WeddingTemplateController**

```php
<?php

namespace App\Http\Controllers;

use App\Models\WeddingTemplate;
use Illuminate\Http\Request;

class WeddingTemplateController extends Controller
{
    public function index()
    {
        $templates = WeddingTemplate::all();
        return response()->json($templates);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:wedding_templates,slug',
            'description' => 'nullable|string',
            'sections_config' => 'nullable|array',
            'styling_config' => 'nullable|array',
            'assets_config' => 'nullable|array',
        ]);

        $template = WeddingTemplate::create($validated);

        return response()->json([
            'success' => true,
            'template' => $template,
        ]);
    }

    public function update(Request $request, $id)
    {
        $template = WeddingTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'sections_config' => 'nullable|array',
            'styling_config' => 'nullable|array',
            'assets_config' => 'nullable|array',
        ]);

        $template->update($validated);

        return response()->json([
            'success' => true,
            'template' => $template,
        ]);
    }

    public function destroy($id)
    {
        $template = WeddingTemplate::findOrFail($id);
        
        if ($template->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete active template',
            ], 400);
        }

        $template->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function activate($id)
    {
        $template = WeddingTemplate::findOrFail($id);
        $template->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'template' => $template,
        ]);
    }

    public function preview($id)
    {
        $template = WeddingTemplate::findOrFail($id);
        
        return response()->json([
            'template' => $template,
            'preview_url' => url('/invitation'),
        ]);
    }
}
```

- [ ] **Step 2: Add routes**

```php
// Template Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/wedding-templates', [WeddingTemplateController::class, 'index'])->name('admin.wedding-templates.index');
    Route::post('/admin/wedding-templates', [WeddingTemplateController::class, 'store'])->name('admin.wedding-templates.store');
    Route::put('/admin/wedding-templates/{id}', [WeddingTemplateController::class, 'update'])->name('admin.wedding-templates.update');
    Route::delete('/admin/wedding-templates/{id}', [WeddingTemplateController::class, 'destroy'])->name('admin.wedding-templates.destroy');
    Route::post('/admin/wedding-templates/{id}/activate', [WeddingTemplateController::class, 'activate'])->name('admin.wedding-templates.activate');
    Route::get('/admin/wedding-templates/{id}/preview', [WeddingTemplateController::class, 'preview'])->name('admin.wedding-templates.preview');
});
```

- [ ] **Step 3: Commit**

```bash
git add app/Http/Controllers/WeddingTemplateController.php routes/web.php
git commit -m "feat: add template management API routes"
```

---

## Task 6: Add Template Management Tab to Admin Dashboard

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php`

- [ ] **Step 1: Add sidebar link**

Add to sidebar navigation:
```html
<li class="nav-item">
    <a class="nav-link" href="#wedding-templates" data-bs-toggle="tab">
        <i class="fas fa-palette"></i> Wedding Templates
    </a>
</li>
```

- [ ] **Step 2: Add mobile nav item**

Add to mobile bottom navigation:
```html
<a href="#wedding-templates" class="mobile-nav-item" data-bs-toggle="tab">
    <i class="fas fa-palette"></i>
    <span>Template</span>
</a>
```

- [ ] **Step 3: Add Wedding Templates tab content**

```html
<div class="tab-pane fade" id="wedding-templates">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-palette me-2"></i>Wedding Templates</h4>
        <button class="btn btn-primary-custom" id="refreshWeddingTemplates">
            <i class="fas fa-sync-alt me-1"></i> Refresh
        </button>
    </div>

    <div class="row" id="weddingTemplatesContainer">
        <!-- Templates will be loaded here via AJAX -->
    </div>
</div>
```

- [ ] **Step 4: Add JavaScript for template management**

Add to existing script section:
```javascript
// ==================== WEDDING TEMPLATE MANAGEMENT ====================
function loadWeddingTemplates() {
    $.ajax({
        url: '{{ route("admin.wedding-templates.index") }}',
        type: 'GET',
        success: function(response) {
            let html = '';
            response.forEach(function(template) {
                const activeBadge = template.is_active 
                    ? '<span class="badge bg-success">Active</span>' 
                    : '<span class="badge bg-secondary">Inactive</span>';
                
                html += `
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="${template.thumbnail || '/assets/images/gallery/slide1.jpg'}" class="card-img-top" alt="${template.name}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">${template.name} ${activeBadge}</h5>
                                <p class="card-text">${template.description || ''}</p>
                                <p class="card-text"><small class="text-muted">Slug: ${template.slug}</small></p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group w-100" role="group">
                                    ${!template.is_active ? 
                                        `<button class="btn btn-sm btn-success activate-wedding-template" data-id="${template.id}">
                                            <i class="fas fa-check me-1"></i> Activate
                                        </button>` : 
                                        '<button class="btn btn-sm btn-secondary" disabled>Active</button>'
                                    }
                                    <a href="/invitation" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye me-1"></i> Preview
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#weddingTemplatesContainer').html(html);
        }
    });
}

// Activate wedding template
$(document).on('click', '.activate-wedding-template', function() {
    const templateId = $(this).data('id');
    showLoading();
    
    $.ajax({
        url: `/admin/wedding-templates/${templateId}/activate`,
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            hideLoading();
            if (response.success) {
                showSuccessMessage('Template berhasil diaktifkan!');
                loadWeddingTemplates();
            }
        },
        error: function() {
            hideLoading();
            showToast('Gagal mengaktifkan template', 'error');
        }
    });
});

// Refresh wedding templates
$('#refreshWeddingTemplates').on('click', function() {
    loadWeddingTemplates();
});
```

- [ ] **Step 5: Call loadWeddingTemplates on page load**

Add to `$(document).ready()`:
```javascript
loadWeddingTemplates();
```

- [ ] **Step 6: Commit**

```bash
git add resources/views/admin/dashboard.blade.php
git commit -m "feat: add wedding template management UI to admin"
```

---

## Task 7: Final Testing & Cleanup

- [ ] **Step 1: Test invitation renders correctly**

Visit `/invitation` - should show the elegant-floral template.

- [ ] **Step 2: Test template switching**

In admin, activate a different template (if you created one) or verify the activate button works.

- [ ] **Step 3: Test guest-specific links**

Visit `/p/invitation?to=GuestName` - should render with guest data.

- [ ] **Step 4: Test form submission**

Submit RSVP form and verify it works.

- [ ] **Step 5: Commit final changes**

```bash
git add -A
git commit -m "feat: complete wedding template system"
```

---

## Summary

This plan converts the existing monolithic invitation into a template-based system:

1. **Database**: `wedding_templates` table with JSON configs
2. **Model**: `WeddingTemplate` with auto-deactivation of other templates
3. **Views**: Template folder structure with sections
4. **Controller**: Reads active template from DB
5. **Admin**: UI to manage and activate templates

**Next Steps After This Plan:**
- Create additional templates (modern-minimal, etc.)
- Add section reorder UI
- Add color/font picker
- Add template import/export
