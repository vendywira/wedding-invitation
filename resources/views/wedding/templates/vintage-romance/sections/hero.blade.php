@php
    $brideName = $template->getSetting('bride_name', 'Isabel');
    $groomName = $template->getSetting('groom_name', 'Jefry');
    $heroSubtitle = $template->getSetting('hero_subtitle', 'a journey of love begins');
    $heroTitle = $template->getSetting('hero_title', 'THE WEDDING OF');
    $coupleName = $guestData->couple_name ?? ($brideName . ' & ' . $groomName);
@endphp

<section id="hero" class="hero-section">
    <div class="hero-desktop d-none d-lg-block">
        <div class="hero-particles" id="hero-particles"></div>
        <div class="hero-content-sticky">
            <p class="hero-subtitle">{{ $heroTitle }}</p>
            <h1 class="hero-couple">{{ $coupleName }}</h1>
            <p class="hero-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d . m . Y') }}</p>
        </div>
    </div>

    <div class="hero-main">
        <div class="hero-decorations">
            <img src="{{ $template->getAssetUrl('floral_border', 'assets/vintage/floral-border.webp') }}" class="hero-floral-top" alt="">
            <img src="{{ $template->getAssetUrl('curtain_decoration', 'assets/vintage/curtain.png') }}" class="hero-curtain hero-curtain-left" alt="">
            <img src="{{ $template->getAssetUrl('curtain_decoration', 'assets/vintage/curtain.png') }}" class="hero-curtain hero-curtain-right" alt="">
            <img src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/flower.png') }}" class="hero-flower hero-flower-1 goyang-2" alt="">
            <img src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/flower.png') }}" class="hero-flower hero-flower-2 goyang-2" alt="">
            <img src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/flower.png') }}" class="hero-flower hero-flower-3 goyang-2" alt="">
            <img src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/flower.png') }}" class="hero-flower hero-flower-4 goyang-2" alt="">
            <img src="{{ $template->getAssetUrl('lamp_decoration', 'assets/vintage/lamp.png') }}" class="hero-lamp goyang-2" alt="">
        </div>

        <div class="hero-card muncul">
            <p class="hero-card-subtitle">{{ $heroTitle }}</p>
            <h2 class="hero-card-couple">
                <span class="hero-card-name">{{ $brideName }}</span>
                <span class="hero-card-and">and</span>
                <span class="hero-card-name">{{ $groomName }}</span>
            </h2>
            <p class="hero-card-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d . m . Y') }}</p>
            <div class="hero-scroll-indicator">
                <img src="{{ $template->getAssetUrl('scroll_gif', 'assets/vintage/scroll.gif') }}" alt="Scroll" class="scroll-gif">
            </div>
        </div>
    </div>

    <div class="hero-mobile d-lg-none">
        <div class="hero-mobile-image">
            <img src="{{ $template->getAssetUrl('bride_photo', 'assets/vintage/bride.jpg') }}" alt="" class="img-fluid">
        </div>
        <div class="hero-mobile-content">
            <p class="hero-mobile-journey">{{ $heroSubtitle }}</p>
            <div class="hero-mobile-verse">
                <p>"Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu istri-istri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya di antaramu rasa kasih dan sayang."</p>
                <p class="verse-ref">QS. Ar-Rum : 21</p>
            </div>
            <img src="{{ $template->getAssetUrl('divider_image', 'assets/vintage/divider.png') }}" alt="" class="hero-mobile-divider">
        </div>
    </div>
</section>
