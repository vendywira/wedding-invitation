@php
    $heroTitle = $template->getSetting('hero_title', 'THE WEDDING OF');
@endphp
<!-- Popup Modal -->
@if(isset($guestData) && $guestData)
<div id="modal" style="opacity: 1; top: 0;">
    <section class="popup">
        {{-- Video pembuka dari Settings → Foto ("Video Pembuka (Hero)"). Kalau
             videonya belum diupload atau switch-nya dimatikan, yang tampil foto
             hero saja — elemen <video> tetap ada (kosong & disembunyikan) karena
             template.js mencari #popupVideo. --}}
        <div class="video-container">
            <video autoplay muted loop playsinline id="popupVideo" class="video-background" preload="auto"
                   webkit-playsinline poster="{{ $heroPhotoUrl }}"
                   @if(empty($heroVideoUrl)) style="display: none;" @endif>
                @if(!empty($heroVideoUrl))
                    <source src="{{ $heroVideoUrl }}" type="{{ $heroVideoType }}">
                @endif
            </video>
            <img src="{{ $heroPhotoUrl }}" alt="Wedding Background" class="fallback-image"
                 @if(empty($heroVideoUrl)) style="display: block;" @endif>
        </div>
        <div class="popup-content" style="margin-top: -60px;">
            <h1>{{ $heroTitle }}</h1>
            <h2>{{ $coupleName }}</h2>
            @if($hashtag)
                <span class="h3"><strong>{{ $hashtag }}</strong></span>
            @endif
            @if($eventDateShort)
                <span class="h3">{{ $eventDateShort }}</span>
            @endif
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
        @foreach($heroSlides as $slideUrl)
            <div class="item @if($loop->first) active @endif">
                <img class="img-responsive" src="{{ $slideUrl }}" alt="{{ $coupleName }}">
            </div>
        @endforeach
        <div class="gradient"></div>
        <div id="header">
            <h1>{{ $heroTitle }}</h1>
            <h2>{{ $coupleName }}</h2>
            @if($hashtag)
                <h3><strong>{{ $hashtag }}</strong></h3>
            @endif
            @if($eventDateShort)
                <h3>{{ $eventDateShort }}</h3>
            @endif
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
