@php
    $countdownTitle = $template->getSetting('countdown_title', 'Counting The Days');
    $coupleName = $guestData->couple_name ?? ($template->getSetting('bride_name', 'Isabel') . ' & ' . $template->getSetting('groom_name', 'Jefry'));
@endphp

<section id="countdown" class="countdown-section">
    <div class="countdown-slideshow">
        <div class="countdown-slide active" style="background-image: url('{{ $template->getAssetUrl('bg_slide_1', 'assets/vintage/bg-slide-1.jpg') }}')"></div>
        <div class="countdown-slide" style="background-image: url('{{ $template->getAssetUrl('bg_slide_2', 'assets/vintage/bg-slide-2.jpg') }}')"></div>
    </div>
    <div class="countdown-overlay"></div>
    <div class="countdown-content muncul">
        <h2 class="countdown-title">{{ $countdownTitle }}</h2>
        <div class="countdown-timer" data-date="{{ $event->event_date_time_start }}">
            <div class="countdown-item">
                <span class="countdown-number days">00</span>
                <span class="countdown-label">Days</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-number hours">00</span>
                <span class="countdown-label">Hours</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-number minutes">00</span>
                <span class="countdown-label">Minutes</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-number seconds">00</span>
                <span class="countdown-label">Seconds</span>
            </div>
        </div>
        <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=Wedding+{{ urlencode($coupleName) }}&dates={{ \Carbon\Carbon::parse($event->event_date_time_start)->format('Ymd\THis') }}/{{ \Carbon\Carbon::parse($event->event_date_time_start)->addHours(3)->format('Ymd\THis') }}&details=Wedding+of+{{ urlencode($coupleName) }}&location={{ urlencode($event->location ?? '') }}"
           class="countdown-btn" target="_blank">
            <i class="fas fa-calendar-plus"></i> SAVE THE DATE
        </a>
    </div>
</section>
