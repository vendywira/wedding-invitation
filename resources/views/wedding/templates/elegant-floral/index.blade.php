@php
    $settings = $template->template_settings ?? [];

    // Satu pernikahan bisa punya beberapa "kulit" template, jadi semua teks dan
    // foto di sini dibaca dari Settings template yang sedang aktif (tab Mempelai,
    // Foto, Gallery, Hadiah, Teks). Nilai default di bawah hanya dipakai selama
    // field-nya belum diisi di dashboard.
    $brideName = $template->getSetting('bride_name', 'Margaretha');
    $groomName = $template->getSetting('groom_name', 'Vendy');
    $brideFullName = $template->getSetting('bride_full_name', 'Margaretha Magdalena Br. Nainggolan');
    $groomFullName = $template->getSetting('groom_full_name', 'I Wayan Vendy Wiranatha S.Kom');
    $coupleName = $groomName . ' & ' . $brideName;
    // Default di bawah ini adalah teks bawaan template, jadi halaman tidak
    // kehilangan isinya selama setting-nya belum diisi dari dashboard.
    $hashtag = trim((string) $template->getSetting('hashtag', '#loVENDYngnyaMARGARETH'));
    $brideAddress = $template->getSetting('bride_address', 'Cipayung, Jakarta Timur, DKI Jakarta');
    $groomAddress = $template->getSetting('groom_address', 'Br. Dinas Sudimara Kaja, Ds. Sudimara, Kec. Tabanan, Kab. Tabanan');
    $brideChildLabel = $template->getSetting('bride_child_label', 'Putri kedua');
    $groomChildLabel = $template->getSetting('groom_child_label', 'Putra pertama');

    $brideFather = $template->getSetting('bride_father', 'Hery Nainggolan');
    $brideMother = $template->getSetting('bride_mother', 'Timak Br Sianturi');
    $groomFather = $template->getSetting('groom_father', 'drh. I Made Sunastra');
    $groomMother = $template->getSetting('groom_mother', 'Triwik Susanti');

    // Foto memakai getAssetUrl(): hasil upload admin disimpan di
    // `template-assets/…` dan disajikan lewat `/storage`, sedangkan getAsset() +
    // asset() menghasilkan URL tanpa `/storage` sehingga fotonya 404.
    // Sampai ada upload, template memakai foto bawaan elegant-floral sendiri.
    // `hero_image` / `background_audio` adalah nama key versi lama template ini;
    // tetap dibaca supaya nilai yang sudah tersimpan tidak hilang.
    $groomPhotoUrl = $template->getAssetUrl('groom_photo') ?: asset('assets/images/gallery/groom.jpg');
    $bridePhotoUrl = $template->getAssetUrl('bride_photo') ?: asset('assets/images/gallery/bride.jpg');
    $heroPhotoUrl = $template->getAssetUrl('hero_photo') ?: $template->getAssetUrl('hero_image') ?: asset('assets/images/gallery/gal-2.jpg');
    // Sumber video pembuka diatur dari Settings → Foto → "Video Pembuka (Hero)":
    // video bawaan template, video yang diupload, atau tanpa video sama sekali
    // (popup pembuka lalu memakai foto hero saja).
    $heroVideoBundledUrl = asset('assets/videos/wedding-bg-2.mp4');
    $heroVideoUploadedUrl = $template->getAssetUrl('hero_video');
    $heroVideoMode = $settings['hero_video_source'] ?? null;

    if ($heroVideoMode === null) {
        // Belum pernah memilih: pakai video yang sudah ada (upload diprioritaskan),
        // kecuali switch lama "Video Pembuka (Hero)" memang dimatikan.
        $heroVideoMode = (($settings['show_hero_video'] ?? '1') === '0') ? 'none' : 'auto';
    }

    $heroVideoUrl = match ($heroVideoMode) {
        'none' => null,
        'custom' => $heroVideoUploadedUrl ?: $heroVideoBundledUrl,
        'default' => $heroVideoBundledUrl,
        'auto' => $heroVideoUploadedUrl ?: $heroVideoBundledUrl,
        default => $heroVideoUploadedUrl ?: $heroVideoBundledUrl,
    };

    // Tipe MIME ikut ekstensi file yang diupload supaya browser tidak menolak
    // video: <source type="video/mp4"> untuk file .webm membuat videonya diam.
    $heroVideoType = 'video/mp4';

    if ($heroVideoUrl) {
        $heroVideoExtension = strtolower(pathinfo(parse_url($heroVideoUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
        $heroVideoType = [
            'webm' => 'video/webm',
            'ogv' => 'video/ogg',
            'mov' => 'video/quicktime',
            'm4v' => 'video/x-m4v',
        ][$heroVideoExtension] ?? 'video/mp4';
    }

    // Tanggal acara utama, dipakai hero modal, header, dan footer.
    $eventDateShort = $event->event_date
        ? \Carbon\Carbon::parse($event->event_date)->locale('id')->translatedFormat('d / m / y')
        : '';
    // Satu grup undangan bisa berisi beberapa acara (akad + resepsi + acara
    // tambahan dari tab Acara), jadi section Info Acara menampilkan semuanya.
    $ceremonies = $event->allCeremonies($template->getSetting('event_resepsi_title', 'Resepsi Pernikahan'));

    // Daftar hadiah (bank / e-wallet / alamat kado) dari Settings → Hadiah.
    $giftAccounts = $template->getGifts();

    // Foto gallery hasil upload dipakai di section Gallery, dan juga sebagai
    // foto tambahan (sisi balik kartu mempelai + slide hero).
    $galleryItems = [];

    foreach ($template->getGalleryImages() as $image) {
        $galleryImageUrl = $template->getGalleryImageUrl($image);

        if ($galleryImageUrl) {
            $galleryItems[] = ['url' => $galleryImageUrl, 'caption' => $image['caption'] ?? ''];
        }
    }

    // Sisi balik kartu mempelai memakai foto gallery ke-2/ke-3 kalau ada, supaya
    // efek flip-nya menampilkan foto pasangan sendiri, bukan foto demo template.
    $groomPhotoBackUrl = $galleryItems[1]['url'] ?? $galleryItems[0]['url'] ?? $groomPhotoUrl;
    $bridePhotoBackUrl = $galleryItems[2]['url'] ?? $galleryItems[1]['url'] ?? $bridePhotoUrl;

    // Slide hero: 2 slide dari Settings → Foto, lalu foto gallery, terakhir slide
    // bawaan template supaya section-nya tidak pernah kosong.
    $heroSlides = array_values(array_filter([
        $template->getAssetUrl('bg_slide_1'),
        $template->getAssetUrl('bg_slide_2'),
    ]));

    foreach ($galleryItems as $galleryItem) {
        $heroSlides[] = $galleryItem['url'];
    }

    $heroSlides = array_slice($heroSlides, 0, 9);

    if (! $heroSlides) {
        for ($slide = 1; $slide <= 9; $slide++) {
            $heroSlides[] = asset('assets/images/gallery/slide' . $slide . '.jpg');
        }
    }

    // Backsound diatur dari Settings → Musik (dipakai juga oleh template
    // vintage-romance): musik bawaan, file yang diupload, atau tanpa musik.
    $backsoundMode = $template->getSetting('backsound', 'default');
    $backsoundUrl = $template->getAssetUrl('backsound_file') ?: $template->getAssetUrl('background_audio');

    if ($backsoundMode === 'none') {
        $backsoundUrl = null;
    } elseif ($backsoundMode !== 'custom' || ! $backsoundUrl) {
        $backsoundUrl = asset('assets/audio/sound-bg.mp3');
    }

    // Toggle section dari Settings → Teks. Toggle bawaan template
    // (`sections_config`) tetap dihormati, jadi keduanya harus "on".
    $sectionOn = function (string $setting, string $section) use ($template, $settings): bool {
        return (($settings[$setting] ?? '1') !== '0') && $template->isSectionEnabled($section);
    };

    $showBridePhoto = $settings['show_bride_photo'] ?? '1';
    $showGroomPhoto = $settings['show_groom_photo'] ?? '1';
    $showCountdown = $sectionOn('show_countdown', 'events');

    $pageTitle = $metaData['title'] ?? ($coupleName . ' - Wedding Invitation');
    $pageDescription = $metaData['description'] ?? ('Undangan Pernikahan ' . $coupleName);
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $pageTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $metaData['robots_meta'] ?? 'noindex, nofollow' }}">
    <meta property="og:title" content="{{ $metaData['og_title'] ?? $pageTitle }}">
    <meta property="og:description" content="{{ $metaData['og_description'] ?? $pageDescription }}">
    <meta property="og:image" content="{{ $metaData['og_image'] ?? $template->getAssetUrl('cover_photo', 'assets/images/og-image.jpg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="canonical" href="{{ $metaData['canonical_url'] ?? url()->current() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/popup/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/' . $template->slug . '/css/template.css') }}">
    <script src="https://kit.fontawesome.com/700f98b672.js" crossorigin="anonymous"></script>
</head>

<body data-aos-easing="ease" data-aos-duration="1500" data-aos-delay="500">
    {{-- Elemen audio selalu ada karena template.js mencari `#myAudio` /
         `#btn-audio`; saat backsound dimatikan, <source> tidak dirender dan
         tombolnya disembunyikan supaya tidak ada musik yang diputar. --}}
    <audio id="myAudio" loop muted autoplay>
        @if($backsoundUrl)
            <source src="{{ $backsoundUrl }}" type="audio/mpeg">
        @endif
    </audio>

    <div id="btn-audio" @if(!$backsoundUrl) style="display: none;" @endif>
        <button id="click-btn" onclick="Play()">
            <img src="{{ asset('assets/images/pause.svg') }}" id="playPausebtn">
        </button>
    </div>

    @if($sectionOn('show_hero', 'hero'))
        @include('wedding.templates.elegant-floral.sections.hero')
    @endif

    <section id="intro"></section>

    @if($sectionOn('show_quote', 'quote'))
        @include('wedding.templates.elegant-floral.sections.quote')
    @endif

    @if($sectionOn('show_pengantar', 'pengantar'))
        @include('wedding.templates.elegant-floral.sections.pengantar')
    @endif

    @if($sectionOn('show_couple', 'couple'))
        @include('wedding.templates.elegant-floral.sections.couple')
    @endif

    @if($sectionOn('show_events', 'events'))
        @include('wedding.templates.elegant-floral.sections.events')
    @endif

    @if($sectionOn('show_gallery', 'gallery'))
        @include('wedding.templates.elegant-floral.sections.gallery')
    @endif

    @if($sectionOn('show_gift', 'gift'))
        @include('wedding.templates.elegant-floral.sections.gift')
    @endif

    @if($sectionOn('show_rsvp', 'rsvp'))
        @include('wedding.templates.elegant-floral.sections.rsvp')
    @endif

    @if($sectionOn('show_best_wishes', 'comments'))
        @include('wedding.templates.elegant-floral.sections.comments')
    @endif

    @if($sectionOn('show_footer', 'footer'))
        @include('wedding.templates.elegant-floral.sections.footer')
    @endif

    <script src="{{ asset('assets/scripts/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/aos.js') }}"></script>
    <script src="{{ asset('assets/scripts/multi-countdown.js') }}"></script>
    <script src="{{ asset('assets/popup/jquery.magnific-popup.min.js') }}"></script>
    {{-- Popup gallery (.image-popup) baru aktif kalau file ini ikut dimuat. --}}
    <script src="{{ asset('assets/popup/magnific-popup-options.js') }}"></script>
    <script src="{{ asset('templates/' . $template->slug . '/js/template.js') }}"></script>
</body>

</html>
