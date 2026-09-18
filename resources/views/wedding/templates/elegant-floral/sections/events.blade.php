@php
    $countdownDate = $event->event_date_time_start ?? null;
@endphp
<!-- Info Acara Section -->
<br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">{{ $template->getSetting('wedding_day_title', 'Info Acara') }}</div>

<section id="acara" data-event-date="{{ $event->event_date ? $event->event_date . ' ' . $event->start_time : '' }}"
         data-location="{{ $event->location }}">
    <div class="container text-center box">
        <div class="row">
            <div class="col-lg-12">
                @if($template->getSetting('wedding_day_subtitle'))
                    <p data-aos="fade-up">{{ $template->getSetting('wedding_day_subtitle') }}</p>
                @endif

                @foreach($ceremonies as $ceremony)
                    @php
                        $ceremonyDate = ! empty($ceremony['event_date'])
                            ? \Carbon\Carbon::parse($ceremony['event_date'])->locale('id')->translatedFormat('l, j F Y')
                            : null;
                        $ceremonyTime = trim(($ceremony['start_time'] ?? '') !== '' && ($ceremony['finish_time'] ?? '') !== ''
                            ? $ceremony['start_time'] . ' - ' . $ceremony['finish_time']
                            : ($ceremony['start_time'] ?? ''));
                    @endphp
                    <div class="ceremony-detail" data-aos="zoom-in-down" @if(!$loop->first) style="margin-top: 25px;" @endif>
                        <h3>{{ $ceremony['title'] }}</h3>
                        <p>
                            @if($ceremony['location'])
                                <i class="far fa-map" aria-hidden="true"></i> {{ $ceremony['location'] }}<br>
                            @endif
                            @if($ceremonyDate)
                                <i class="far fa-calendar-check" aria-hidden="true"></i> {{ $ceremonyDate }}<br>
                            @endif
                            @if($ceremonyTime)
                                <i class="far fa-clock" aria-hidden="true"></i> {{ $ceremonyTime }}<br>
                            @endif
                            @if($ceremony['address'])
                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $ceremony['address'] }}
                            @endif
                        </p>
                        @if($ceremony['google_map_link'])
                            <a href="{{ $ceremony['google_map_link'] }}" target="_blank" class="btn-map">
                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i> Google Map
                            </a>
                        @endif
                    </div>
                @endforeach

                <div data-aos="fade-up">
                    <a href="javascript:void(0)" class="btn-map btn-save-date" onclick="smartAddToCalendar()" style="margin-left: 15px">
                        <i class="far fa-calendar-plus" aria-hidden="true"></i> Save the Date
                    </a>
                </div>
                <br>

                @if($showCountdown && $countdownDate)
                    <!-- Countdown Timer -->
                    <div class="countdown show" data-aos="zoom-in" data-date="{{ $countdownDate }}">
                        <div class="text"><h2>{{ $template->getSetting('countdown_title', 'Waktu Menuju Acara') }}</h2></div>
                        <div class="running" style="display: flex;">
                            <timer>
                                <span class="days">00</span>:<span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span>
                            </timer>
                        </div>
                        <div class="labels">
                            <span class="label-days">Hari</span>
                            <span class="label-hours">Jam</span>
                            <span class="label-minutes">Menit</span>
                            <span class="label-seconds">Detik</span>
                        </div>
                        <div class="break"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
