<section id="hero" class="hero-section">
    <div class="hero-desktop d-none d-lg-block">
        <div class="hero-particles" id="hero-particles"></div>
        <div class="hero-content-sticky">
            <p class="hero-subtitle">THE WEDDING OF</p>
            <h1 class="hero-couple">{{ $guestData->couple_name ?? 'Isabel & Jefry' }}</h1>
            <p class="hero-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d . m . Y') }}</p>
        </div>
    </div>

    <div class="hero-main">
        <div class="hero-decorations">
            <img src="{{ asset($template->getAsset('floral_border', 'assets/vintage/floral-border.webp')) }}" class="hero-floral-top" alt="">
            <img src="{{ asset($template->getAsset('curtain_decoration', 'assets/vintage/curtain.png')) }}" class="hero-curtain hero-curtain-left" alt="">
            <img src="{{ asset($template->getAsset('curtain_decoration', 'assets/vintage/curtain.png')) }}" class="hero-curtain hero-curtain-right" alt="">
            <img src="{{ asset($template->getAsset('flower_decoration', 'assets/vintage/flower.png')) }}" class="hero-flower hero-flower-1 goyang-2" alt="">
            <img src="{{ asset($template->getAsset('flower_decoration', 'assets/vintage/flower.png')) }}" class="hero-flower hero-flower-2 goyang-2" alt="">
            <img src="{{ asset($template->getAsset('flower_decoration', 'assets/vintage/flower.png')) }}" class="hero-flower hero-flower-3 goyang-2" alt="">
            <img src="{{ asset($template->getAsset('flower_decoration', 'assets/vintage/flower.png')) }}" class="hero-flower hero-flower-4 goyang-2" alt="">
            <img src="{{ asset($template->getAsset('lamp_decoration', 'assets/vintage/lamp.png')) }}" class="hero-lamp goyang-2" alt="">
        </div>

        <div class="hero-card muncul">
            <p class="hero-card-subtitle">THE WEDDING OF</p>
            <h2 class="hero-card-couple">
                <span class="hero-card-name">{{ $guestData->bride_name ?? 'Isabel' }}</span>
                <span class="hero-card-and">and</span>
                <span class="hero-card-name">{{ $guestData->groom_name ?? 'Jefry' }}</span>
            </h2>
            <p class="hero-card-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d . m . Y') }}</p>
            <div class="hero-scroll-indicator">
                <img src="{{ asset($template->getAsset('scroll_gif', 'assets/vintage/scroll.gif')) }}" alt="Scroll" class="scroll-gif">
            </div>
        </div>
    </div>

    <div class="hero-mobile d-lg-none">
        <div class="hero-mobile-image">
            <img src="{{ asset($template->getAsset('bride_photo', 'assets/vintage/bride.jpg')) }}" alt="" class="img-fluid">
        </div>
        <div class="hero-mobile-content">
            <p class="hero-mobile-journey">a journey of love begins</p>
            <div class="hero-mobile-verse">
                <p>"Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu istri-istri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya di antaramu rasa kasih dan sayang."</p>
                <p class="verse-ref">QS. Ar-Rum : 21</p>
            </div>
            <img src="{{ asset($template->getAsset('divider_image', 'assets/vintage/divider.png')) }}" alt="" class="hero-mobile-divider">
        </div>
    </div>
</section>
