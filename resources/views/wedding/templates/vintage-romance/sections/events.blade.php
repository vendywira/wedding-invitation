<section id="events" class="events-section">
    <div class="events-decorations">
        <img src="{{ $template->getAssetUrl('divider_image', 'assets/vintage/divider.png') }}" class="events-divider-top" alt="" style="width:100%; position:absolute; bottom:0; right:0; max-width:250px; opacity:0.3;">
        <img src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/flower.png') }}" class="events-flower events-flower-1 goyang-2" alt="">
        <img src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/flower.png') }}" class="events-flower events-flower-2 goyang-2" alt="">
        <img src="{{ $template->getAssetUrl('lamp_decoration', 'assets/vintage/lamp.png') }}" class="events-lamp goyang-2" alt="">
        <img src="{{ $template->getAssetUrl('curtain_decoration', 'assets/vintage/curtain.png') }}" class="events-curtain-left" alt="">
        <img src="{{ $template->getAssetUrl('curtain_decoration', 'assets/vintage/curtain.png') }}" class="events-curtain-right" alt="">
    </div>

    <div class="events-container">
        <h2 class="events-title muncul">Wedding Day</h2>
        <p class="events-subtitle muncul">InsyaAllah akan dilaksanakan pada:</p>
        <p class="events-date muncul">{{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}</p>

        <img src="{{ $template->getAssetUrl('divider_image', 'assets/vintage/divider.png') }}" class="events-divider muncul" alt="">

        <div class="events-grid">
            <div class="event-card muncul">
                <h3 class="event-type">Akad Nikah</h3>
                <p class="event-time">{{ $event->start_time }}</p>
            </div>
            <div class="event-separator">|</div>
            <div class="event-card muncul">
                <h3 class="event-type">Resepsi Pernikahan</h3>
                <p class="event-time">{{ $event->finish_time }}</p>
            </div>
        </div>

        <div class="events-location muncul">
            <i class="fas fa-map-marker-alt"></i>
            <p class="events-venue">{{ $event->location }}</p>
            <p class="events-address">{{ $event->address ?? '' }}</p>
            @if($event->google_map_link)
                <a href="{{ $event->google_map_link }}" class="events-map-btn" target="_blank">
                    <i class="fas fa-location-arrow"></i> Google Maps
                </a>
            @endif
        </div>
    </div>
</section>
