<section id="gallery" class="gallery-section">
    <div class="gallery-container">
        <h2 class="gallery-title muncul">the Moments of</h2>
        <p class="gallery-subtitle muncul">{{ $guestData->bride_name ?? 'Isabel' }} & {{ $guestData->groom_name ?? 'Jefry' }}</p>

        @if($template->getAsset('youtube_url'))
        <div class="gallery-video muncul">
            <iframe src="https://www.youtube.com/embed/{{ $template->getAsset('youtube_url') }}" title="Wedding Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        @endif

        <div class="gallery-carousel muncul">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset($template->getAsset('bride_photo', 'assets/vintage/bride.jpg')) }}" alt="" class="img-fluid">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset($template->getAsset('groom_photo', 'assets/vintage/groom.jpg')) }}" alt="" class="img-fluid">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset($template->getAsset('story_image', 'assets/vintage/story.jpg')) }}" alt="" class="img-fluid">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset($template->getAsset('gallery_1', 'assets/vintage/gallery-1.jpg')) }}" alt="" class="img-fluid">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset($template->getAsset('gallery_2', 'assets/vintage/gallery-2.jpg')) }}" alt="" class="img-fluid">
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <div class="gallery-grid muncul">
            <a href="{{ asset($template->getAsset('bride_photo', 'assets/vintage/bride.jpg')) }}" class="gallery-item" data-lightbox="gallery">
                <img src="{{ asset($template->getAsset('bride_photo', 'assets/vintage/bride.jpg')) }}" alt="" class="img-fluid">
            </a>
            <a href="{{ asset($template->getAsset('groom_photo', 'assets/vintage/groom.jpg')) }}" class="gallery-item" data-lightbox="gallery">
                <img src="{{ asset($template->getAsset('groom_photo', 'assets/vintage/groom.jpg')) }}" alt="" class="img-fluid">
            </a>
            <a href="{{ asset($template->getAsset('story_image', 'assets/vintage/story.jpg')) }}" class="gallery-item" data-lightbox="gallery">
                <img src="{{ asset($template->getAsset('story_image', 'assets/vintage/story.jpg')) }}" alt="" class="img-fluid">
            </a>
            <a href="{{ asset($template->getAsset('gallery_1', 'assets/vintage/gallery-1.jpg')) }}" class="gallery-item" data-lightbox="gallery">
                <img src="{{ asset($template->getAsset('gallery_1', 'assets/vintage/gallery-1.jpg')) }}" alt="" class="img-fluid">
            </a>
        </div>
    </div>
</section>
