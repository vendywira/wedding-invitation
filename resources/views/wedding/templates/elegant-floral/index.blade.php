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
    <link rel="stylesheet" href="{{ asset('templates/' . $template->slug . '/css/template.css') }}">
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

    <section id="intro"></section>

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
    <script src="{{ asset('templates/' . $template->slug . '/js/template.js') }}"></script>
</body>
</html>
