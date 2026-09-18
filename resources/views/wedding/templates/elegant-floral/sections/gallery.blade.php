@php
    // Foto gallery dari Settings → Gallery. Selama admin belum mengupload apa
    // pun, section-nya memakai foto bawaan template supaya tidak kosong.
    $photos = $galleryItems ?? [];

    if (! $photos) {
        for ($i = 1; $i <= 14; $i++) {
            $photos[] = ['url' => asset('assets/images/gallery/gal-' . $i . '.jpg'), 'caption' => ''];
        }
    }
@endphp
<!-- Gallery Section -->
<br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">{{ $template->getSetting('gallery_title', 'Photo Gallery') }}</div>

@if($template->getSetting('gallery_subtitle'))
    <div class="container text-center" data-aos="zoom-in">
        <p>{{ $template->getSetting('gallery_subtitle') }}</p>
    </div>
@endif

<div class="container">
    <div class="masonry">
        @foreach($photos as $photo)
            <div class="galley" data-aos="zoom-in">
                <a class="image-popup" href="{{ $photo['url'] }}" @if(!empty($photo['caption'])) title="{{ $photo['caption'] }}" @endif>
                    <img src="{{ $photo['url'] }}" class="img-responsive border" alt="{{ $photo['caption'] ?: 'Photo Gallery' }}" loading="lazy">
                </a>
            </div>
        @endforeach
    </div>
</div>
