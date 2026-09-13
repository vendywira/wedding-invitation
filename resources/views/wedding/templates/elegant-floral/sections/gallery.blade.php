<!-- Gallery Section -->
<br>
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
