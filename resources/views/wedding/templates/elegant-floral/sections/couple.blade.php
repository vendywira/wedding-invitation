@php
    // `show_bride_photo` / `show_groom_photo` mematikan hanya fotonya; nama dan
    // orang tuanya tetap tampil (sama seperti template vintage-romance).
    $showBride = ($showBridePhoto ?? '1') !== '0';
    $showGroom = ($showGroomPhoto ?? '1') !== '0';
@endphp
<!-- Tentang Pasangan Section -->
<section id="tentang-pasangan">
    <div class="container text-center">
        <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
        <div class="tittle-section" data-aos="zoom-in-down">{{ $template->getSetting('couple_subtitle', 'Sang Mempelai') }}</div>
        <div class="row">
            <!-- Mempelai Pria -->
            <div class="col-lg-6">
                @if($showGroom)
                    <div class="flip-container" data-aos="zoom-in">
                        <div class="flipper" id="flipper-groom">
                            <div class="front">
                                <div class="image-wrapper">
                                    <img src="{{ $groomPhotoUrl }}" class="profile-image" alt="{{ $groomFullName }}">
                                </div>
                            </div>
                            <div class="back">
                                <div class="image-wrapper">
                                    <img src="{{ $groomPhotoBackUrl }}" class="profile-image" alt="{{ $groomFullName }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <h2 data-aos="flip-left">{{ $groomFullName }}</h2>
                @if($groomFather || $groomMother || $groomAddress)
                    <div data-aos="fade-up">
                        <p>{{ $groomChildLabel }} dari pasangan<br>
                            Bapak {{ $groomFather }} dengan Ibu {{ $groomMother }}
                            @if($groomAddress)
                                <br><br>
                                <i class="fa fa-map-marker-alt" aria-hidden="true"></i> {{ $groomAddress }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
            <!-- Mempelai Wanita -->
            <div class="col-lg-6">
                @if($showBride)
                    <div class="flip-container" data-aos="zoom-in">
                        <div class="flipper" id="flipper-bride">
                            <div class="front">
                                <div class="image-wrapper">
                                    <img src="{{ $bridePhotoUrl }}" class="profile-image" alt="{{ $brideFullName }}">
                                </div>
                            </div>
                            <div class="back">
                                <div class="image-wrapper">
                                    <img src="{{ $bridePhotoBackUrl }}" class="profile-image" alt="{{ $brideFullName }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <h2 data-aos="flip-left">{{ $brideFullName }}</h2>
                @if($brideFather || $brideMother || $brideAddress)
                    <div data-aos="fade-up">
                        <p>{{ $brideChildLabel }} dari pasangan<br>
                            Bapak {{ $brideFather }} dengan Ibu {{ $brideMother }}
                            @if($brideAddress)
                                <br><br>
                                <i class="fa fa-map-marker-alt" aria-hidden="true"></i> {{ $brideAddress }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
