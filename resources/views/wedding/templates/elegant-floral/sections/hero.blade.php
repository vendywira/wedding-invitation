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
