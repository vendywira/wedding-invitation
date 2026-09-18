@php
    $verse = $template->getSetting('hero_verse', '"Demikianlah mereka bukan lagi dua, melainkan satu. Karena itu, apa yang telah dipersatukan Allah, tidak boleh diceraikan oleh manusia."');
    $verseSource = $template->getSetting('hero_verse_source', '(Matius 19:6)');
@endphp
<!-- Ayat Alkitab Section -->
<section id="quote" align="center">
    <div data-aos="zoom-in" align="center">
        <img src="{{ asset('assets/images/ornt.png') }}" width="30"><br>
    </div>
    <div class="container text-center box" data-aos="zoom-in-up">
        {{ $verse }}
        @if($verseSource)
            <h3>{{ $verseSource }}</h3>
        @endif
        <h2>{{ $template->getSetting('hero_subtitle', 'Kami berdoa agar pernikahan ini senantiasa diberkati oleh Kasih Karunia Tuhan, dipersatukan dalam cinta Kristus, dan menjadi kesaksian bagi kemuliaan-Nya.') }}</h2>
    </div>
</section>
