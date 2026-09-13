<!-- Info Acara Section -->
<br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Info Acara</div>

<section id="acara" data-event-date="{{ $event->event_date }} {{ $event->start_time }}" data-location="{{ $event->location }}">
    <div class="container text-center box">
        <div class="row">
            <div class="col-lg-12">
                <div data-aos="zoom-in-down">
                    <p>
                        <i class="far fa-map" aria-hidden="true"></i> {{ $event->location }}<br>
                        <i class="far fa-calendar-check" aria-hidden="true"></i> {{ (new \IntlDateFormatter('id_ID',
                        \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, 'Asia/Jakarta',
                        \IntlDateFormatter::GREGORIAN, 'EEEE, d MMMM y'))->format(new DateTime($event->event_date))
                        }}<br>
                        <i class="far fa-clock" aria-hidden="true"></i> {{ $event->start_time }} WITA - {{
                        $event->finish_time }}<br>
                    </p>
                </div>
                <div data-aos="fade-up">
                    <a href="{{ $event->google_map_link }}" target="_blank" class="btn-map">
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i> Google Map
                    </a>
                    <a href="javascript:void(0)" class="btn-map btn-save-date" onclick="smartAddToCalendar()" style="margin-left: 15px">
                        <i class="far fa-calendar-plus" aria-hidden="true"></i> Save the Date
                    </a>
                </div>
                <br>
                <!-- Countdown Timer -->
                <div class="countdown show" data-aos="zoom-in" data-date="{{ $event->event_date_time_start }}" style="display: block;">
                    <div class="text"><h2>Waktu Menuju Acara</h2></div>
                    <div class="running" style="display: flex;">
                        <timer>
                            <span class="days">07</span>:<span class="hours">13</span>:<span class="minutes">02</span>:<span class="seconds">16</span>
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
            </div>
        </div>
    </div>
</section>
