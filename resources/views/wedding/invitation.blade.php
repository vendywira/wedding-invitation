<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pernikahan Vendy & Margareth</title>
    <meta property="og:title" content="Pernikahan Vendy & Margareth">
    <meta property="og:image" content="{{ asset('assets/images/thumb.jpg') }}">
    <meta property="og:description" content="Undangan pada Rabu, 12 November 2025">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style3.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/popup/magnific-popup.css') }}">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/700f98b672.js" crossorigin="anonymous"></script>

    <style>
        #modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: all 0.5s ease-in-out;
        }

        .popup {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            max-width: 90%;
        }

        .popup h1 {
            margin-top: 0;
            color: #333;
        }

        .popup h2 {
            color: #555;
            margin-bottom: 10px;
        }

        .popup .h3 {
            font-size: 1.2em;
            color: #777;
        }

        .yth {
            margin-top: 20px;
        }

        .nama-tamu {
            font-weight: bold;
            font-size: 1.5em;
            color: #e44d26;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        #hidePlay {
            background-color: #e44d26;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        #hidePlay:hover {
            background-color: #d14420;
        }

        /* Video Background Styles */
        .video-background {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translateX(-50%) translateY(-50%);
            z-index: 1;
            object-fit: cover;
        }

        .popup-content {
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .popup::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
            border-radius: 10px;
        }
    </style>
</head>

<body data-aos-easing="ease" data-aos-duration="1500" data-aos-delay="500">
<!-- Audio dengan autoplay dan muted untuk bypass browser restrictions -->
<audio id="myAudio" loop muted autoplay>
    <source src="{{ asset('assets/audio/sound-bg.mp3') }}" type="audio/mp3">
</audio>

<div id="btn-audio">
    <button id="click-btn" onclick="Play()">
        <img src="{{ asset('assets/images/pause.svg') }}" id="playPausebtn">
    </button>
</div>

<!-- Popup Modal -->
@if(isset($guestData) && $guestData)
<div id="modal" style="opacity: 1; top: 0;">
    <section class="popup">
        <!-- Video Background -->
        <video autoplay muted loop id="popupVideo" class="video-background">
            <source src="{{ asset('assets/videos/wedding-bg.mp4') }}" type="video/mp4">
        </video>

        <div class="popup-content" style="margin-top: -60px;">
            <h1>THE WEDDING OF</h1>
            <h2>Vendy & Margareth</h2>
            <span class="h3">12 / 11 / 25</span><br><br><br><br><br><br><br><br><br><br><br><br>
            <div class="yth">
                Kepada Yth Bapak/Ibu/Saudara/i:<br>
                <div class="nama-tamu">{{$guestData->name ?? request()->get('to', 'Tamu Undangan') }}</div>
                <button id="hidePlay" onclick="hidePlay()">
                    <i class="fas fa-envelope-open-text" aria-hidden="true"></i> Buka Undangan
                </button>
            </div>
        </div>
    </section>
</div>
@endif

<!-- Rest of your HTML content remains the same -->
<section id="intro"></section>

<!-- Carousel Section -->
<div id="carousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="3000" data-pause="false">
    <div class="carousel-inner carousel-zoom">
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide1.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide2.jpg') }}"></div>
        <div class="item active"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide3.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide4.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide5.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide6.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide7.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide8.jpg') }}"></div>
        <div class="item"><img class="img-responsive" src="{{ asset('assets/images/gallery/slide9.jpg') }}"></div>
        <div class="gradient"></div>

        <div id="header">
            <h1>PERNIKAHAN</h1>
            <h2>Vendy & Margareth</h2>
            <h3>12 / 11 / 25</h3>
        </div>

        <!-- SVG Waves -->
        <svg class="bg-wave1" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 500 100"
             xml:space="preserve">
                <style type="text/css">.st0 {
                        opacity: .5
                    }</style>
            <path fill="#fff" transform="rotate(90deg)" class="st0"
                  d="M0,0.1c0,0,37-3.5,221,34.4s279,17.6,279,17.6v20.3H0V0.1z"></path>
            <path fill="#fff" transform="rotate(90deg)" class="st0"
                  d="M500,0.1c0,0-37-3.5-221,34.4S0,52.1,0,52.1l0,20.3h500V0.1z"></path>
            <path fill="#fff" transform="rotate(90deg)" class="st0"
                  d="M0,16.4c0,0,37-2.7,221,26.6s279,13.6,279,13.6v15.7H0V16.4z"></path>
            <path fill="#fff" transform="rotate(90deg)" class="st0"
                  d="M500,16.4c0,0-37-2.7-221,26.6S0,56.6,0,56.6l0,15.7h500V16.4z"></path>
            <path fill="#fff" transform="rotate(90deg)" class="st0"
                  d="M0,35.3c0,0,37-1.8,221,17.6s279,9,279,9v10.4H0V35.3z"></path>
            <path fill="#fff" transform="rotate(90deg)" class="st0"
                  d="M500,35.3c0,0-37-1.8-221,17.6s-279,9-279,9l0,10.4h500V35.3z"></path>
            </svg>
    </div>
</div>

<!-- Ayat Alkitab Section -->
<section id="quote" align="center">
    <div data-aos="zoom-in" align="center">
        <img src="{{ asset('assets/images/ornt.png') }}" width="30"><br>
    </div>
    <div class="container text-center box" data-aos="zoom-in-up">
        "Demikianlah mereka bukan lagi dua, melainkan satu. Karena itu, apa yang telah dipersatukan Allah, tidak boleh
        diceraikan oleh manusia."
        <h3>(Matius 19:6)</h3>
        <h2>Kami berdoa agar pernikahan ini senantiasa diberkati oleh Kasih Karunia Tuhan, dipersatukan dalam cinta
            Kristus, dan menjadi kesaksian bagi kemuliaan-Nya.</h2>
    </div>
</section>

<!-- Pengantar Section -->
<section id="pengantar" class="container text-center" data-aos="zoom-in-down">
    Atas Kasih dan Anugerah Tuhan Yesus Kristus, dengan sukacita kami memohon kehadiran dan doa restu
    Bapak/Ibu/Saudara/i dalam menyaksikan dan mendoakan pernikahan putra-putri kami.
</section>

<!-- Tentang Pasangan Section -->
<section id="tentang-pasangan">
    <div class="container text-center">
        <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
        <div class="tittle-section" data-aos="zoom-in-down">Sang Mempelai</div>

        <div class="row">
            <!-- Mempelai Pria -->
            <div class="col-lg-6">
                <div class="flip-container" data-aos="zoom-in">
                    <div class="flipper" id="flipper-groom">
                        <div class="front">
                            <div class="image-wrapper">
                                <img src="{{ asset('assets/images/gallery/groom.jpg') }}" class="profile-image">
                            </div>
                        </div>
                        <div class="back">
                            <div class="image-wrapper">
                                <img src="{{ asset('assets/images/gallery/groom3.jpg') }}" class="profile-image">
                            </div>
                        </div>
                    </div>
                </div>
                <h2 data-aos="flip-left">I Wayan Vendy Wiranatha S.Kom</h2>
                <div data-aos="fade-up">
                    <p>Putra pertama dari pasangan<br>
                        Bapak drh. I Made Sunastra dengan Ibu Triwik Susanti<br><br>
                        <i class="fa fa-map-marker-alt" aria-hidden="true"></i> Br. Dinas Sudimara Kaja, Ds. Sudimara,
                        Kec. Tabanan, Kab. Tabanan</p>
                    <a href="https://www.instagram.com/gustiVendya/"><i class="fab fa-2x fa-instagram"
                                                                        aria-hidden="true"></i></a>
                </div>
            </div>

            <!-- Mempelai Wanita -->
            <div class="col-lg-6">
                <div class="flip-container" data-aos="zoom-in">
                    <div class="flipper" id="flipper-bride">
                        <div class="front">
                            <div class="image-wrapper">
                                <img src="{{ asset('assets/images/gallery/bride.jpg') }}" class="profile-image">
                            </div>
                        </div>
                        <div class="back">
                            <div class="image-wrapper">
                                <img src="{{ asset('assets/images/gallery/bride3.jpg') }}" class="profile-image">
                            </div>
                        </div>
                    </div>
                </div>
                <h2 data-aos="flip-left">Margaretha Magdalena Br. Nainggolan</h2>
                <div data-aos="fade-up">
                    <p>Putri kedua dari pasangan<br>
                        Bapak Hery Nainggolan dengan Ibu Timak Br Sianturi<br><br>
                        <i class="fa fa-map-marker-alt" aria-hidden="true"></i> Cipayung, Jakarta Timur, DKI Jakarta</p>
                    <a href="https://www.instagram.com/Margarethrnt_/"><i class="fab fa-2x fa-instagram"
                                                                          aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Info Acara Section -->
<br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Info Acara</div>

<section id="acara">
    <div class="container text-center box">
        <div class="row">
            <div class="col-lg-12">
                <div data-aos="zoom-in-down">
                    <p>
                        <i class="far fa-map" aria-hidden="true"></i> Wedding Venue Taman Prakerti Bhuana Indraprasta Ballroom<br>
                        <i class="far fa-calendar-check" aria-hidden="true"></i> Rabu, 12 November 2025<br>
                        <i class="far fa-clock" aria-hidden="true"></i> 17.00 WITA - 21.00 WITA<br>
                    </p>
                </div>
                <div data-aos="fade-up">
                    <a href="https://maps.app.goo.gl/gMvgt3TUPqrMAqRT7" target="_blank" class="btn-map">
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i> Google Map
                    </a>
                </div>
                <br>

                <!-- Countdown Timer -->
                <div class="countdown show" data-aos="zoom-in" data-date="2025/11/12 17:00:00" style="display: block;">
                    <div class="text"><h2>Waktu Menuju Acara</h2></div>
                    <div class="running" style="display: flex;">
                        <timer>
                            <span class="days">07</span>:<span class="hours">13</span>:<span
                                class="minutes">02</span>:<span class="seconds">16</span>
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

<!-- Kado Digital Section -->
<br><br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Kado Digital</div>

<section id="gift">
    <div class="container text-center" data-aos="zoom-in">
        Bagi keluarga dan sahabat yang ingin mengirimkan hadiah / kado secara cashless, silahkan mengirimkan
        melalui:<br><br>
        <div data-aos="zoom-in" data-toggle="modal" data-target="#myModal2" class="btn">
            <i class="fas fa-share" aria-hidden="true"></i> Klik Disini
        </div>
        <br>
    </div>

    <!-- Modal Kado Digital -->
    <div class="modal fade" id="myModal2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="{{ asset('assets/images/close.svg') }}" width="20">
                    </button>
                </div>
                <div class="modal-body" align="center">
                    <div id="covid2">
                        <div style="margin-bottom:8px;">
                            <img src="{{ asset('assets/images/bca.png') }}" width="150px;">
                        </div>
                        <div id="p1">1420421377</div>
                        <strong>a/n I Wayan Vendy Wiranatha</strong><br><br>
                        <button onclick="copyToClipboard('#p1')" class="button-gift">
                            <i class="fas fa-copy" aria-hidden="true"></i> Salin Nomor
                        </button>
                        <br><br>
                        <div style="margin-bottom:8px;">
                            <img src="{{ asset('assets/images/jago.jpg') }}" width="150px;">
                        </div>
                        <div id="p1">109246172960</div>
                        <strong>a/n I Wayan Vendy Wiranatha</strong><br><br>
                        <button onclick="copyToClipboard('#p1')" class="button-gift">
                            <i class="fas fa-copy" aria-hidden="true"></i> Salin Nomor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Konfirmasi Tamu Section -->
<br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Konfirmasi Tamu</div>

<section id="konfirmasi" data-aos="zoom-in">
    <div class="konfirmasi-container">
        <div class="konfirmasi-wrapper">
            <div class="konfirmasi-box">
                <form id="konfirmasi-form" method="POST" autocomplete="off" class="form-group">
                    @csrf
                    <input type="hidden" name="guest_code" value="{{ $guest->guest_code ?? '' }}">

                    <div class="text-center mb-4">
                        <h3 class="form-title">Konfirmasi Kehadiran</h3>
                        <p class="form-subtitle">Dimohon kesediaannya untuk mengisi form kehadiran undangan kami</p>
                    </div>

                    <div class="form-group-row">
                        <div class="col-12">
                            <input type="text" class="form-control custom-input" name="name" id="nama-fm" placeholder="Nama Lengkap" required
                                   value="{{ $guestData->name ?? request()->get('to') }}">
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="col-12">
                            <select class="form-control custom-select" name="guest_count" id="jumlah-fm" required>
                                <option value="" disabled selected>Pilih Jumlah Tamu</option>
                                <option value="1">1 Orang</option>
                                <option value="2">2 Orang</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="col-12">
                            <select class="form-control custom-select" name="attendance" id="kehadiran-fm" required>
                                <option value="" disabled selected>Konfirmasi Kehadiran</option>
                                <option value="Hadir">Iya, Saya Hadir</option>
                                <option value="Tidak Hadir">Maaf, Saya Tidak Hadir</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="col-12">
                            <textarea class="form-control custom-textarea" maxlength="250" name="message" id="pesan-fm" rows="3"
                                      placeholder="Ketikan Ucapan / Doa untuk mempelai"></textarea>
                            <div class="char-counter">
                                <span id="char-count">0</span>/250 karakter
                            </div>
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-submit" name="send-konfirmasi" id="send-konfirmasi">
                                <span class="btn-text">KIRIM KONFIRMASI</span>
                                <div class="btn-loading d-none">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Mengirim...
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Loading Section -->
<section id="loadingkonfirmasiform" class="invisible">
    <div data-aos="zoom-in">
        <div align="center">Mohon menunggu kami sedang memproses data anda</div>
    </div>
</section>

<!-- Ucapan Section -->
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Kiriman Ucapan</div>

<section id="komentar">
    <div class="container komentar-box">
        <div class="container-fluid">
            <div class="col-xs-8 col-sm-6" id="totalDoa-view" data-aos="zoom-in">
                <strong>{{ $messages->count() ?? 0 }} Ucapan</strong>
            </div>

            <div class="col-xs-4 col-sm-6" align="right">
                <select class="form-control" name="konfirmasi-display-order" id="konfirmasi-display-order"
                        style="width: 100px;" data-aos="zoom-in">
                    <option selected value="new">Terbaru</option>
                    <option value="old">Terlama</option>
                </select>
            </div>
        </div>

        <div class="container-fluid" id="listkomentar" data-aos="zoom-in">
            @if(isset($messages) && $messages->count() > 0)
            @foreach($messages as $message)
            <div class="comment-item">
                <strong>{{ $message->name }}</strong>
                <small>{{ $message->created_at->format('d M Y H:i') }}</small>
                <p>{{ $message->message }}</p>
                <div class="attendance-badge {{ strtolower($message->attendance) }}">
                    {{ $message->attendance }} ({{ $message->guest_count }} orang)
                </div>
            </div>
            @endforeach
            @else
            <div class="text-center" style="padding: 20px;">
                <p>Belum ada ucapan. Jadilah yang pertama mengucapkan selamat!</p>
            </div>
            @endif
        </div>

        <div align="center" data-aos="zoom-in-down">
            <button type="button" id="showallcomment-btn" class="btn invisible" name="submit">
                TAMPILKAN SEMUA ({{ $messages->count() ?? 0 }})
            </button>
        </div>
    </div>
</section>

<!-- Penutup Section -->
<br><br>
<section id="penutup" class="container text-center" data-aos="zoom-in-down">
    Merupakan suatu kehormatan dan kebahagiaan kami, apabila Bapak/Ibu/Saudara/i berkenan hadir memberikan doa restu<br>
</section>

<!-- Footer -->
<br><br>
<section id="footer-wave">
    <section id="footer-top"></section>
    <svg class="bg-wave2" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 500 75"
         xml:space="preserve">
            <style type="text/css">.st0 {
                    opacity: .4
                }</style>
        <path fill="#000" transform="rotate(90deg)" class="st0"
              d="M0,0.1c0,0,37-3.5,221,34.4s279,17.6,279,17.6v20.3H0V0.1z"></path>
        <path fill="#000" transform="rotate(90deg)" class="st0"
              d="M500,0.1c0,0-37-3.5-221,34.4S0,52.1,0,52.1l0,20.3h500V0.1z"></path>
        <path fill="#000" transform="rotate(90deg)" class="st0"
              d="M0,16.4c0,0,37-2.7,221,26.6s279,13.6,279,13.6v15.7H0V16.4z"></path>
        <path fill="#000" transform="rotate(90deg)" class="st0"
              d="M500,16.4c0,0-37-2.7-221,26.6S0,56.6,0,56.6l0,15.7h500V16.4z"></path>
        <path fill="#000" transform="rotate(90deg)" class="st0"
              d="M0,35.3c0,0,37-1.8,221,17.6s279,9,279,9v10.4H0V35.3z"></path>
        <path fill="#000" transform="rotate(90deg)" class="st0"
              d="M500,35.3c0,0-37-1.8-221,17.6s-279,9-279,9l0,10.4h500V35.3z"></path>
        </svg>
</section>

<section id="footer" class="container">
    <div class="col-sm-12 text-center">
        <h1 data-aos="fade-down">Kami Yang Berbahagia</h1>
        <h2 data-aos="fade-up">Vendy & Margareth</h2>
        <h3>© 2025 Wedding Invitation by <a href="https://linkedin.com/in/vendywira">I Wayan Vendy Wiranatha</a></h3>
        <div class="col-md-12 col-sm-12 social">
            <a href="https://www.facebook.com/vendy.wiranatha" target="_blank"><i
                    class="fab fa-1x fa-facebook-f" aria-hidden="true"></i></a>
            <a href="https://www.instagram.com/vendywira/" target="_blank"><i class="fab fa-1x fa-instagram"
                                                                              aria-hidden="true"></i></a>
            <a href="https://linkedin.com/in/vendywira" target="_blank"><i class="fab fa-1x fa-linkedin"
                                                                           aria-hidden="true"></i></a>
        </div>
    </div>
</section>

<!-- Success Modal -->
<div id="successModal" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog"
     aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="padding:20px;">
            <div class="row">
                <div class="col-md-12 p-5 text-center">
                    <img src="{{ asset('assets/images/check.svg') }}" width="40px"><br>
                </div>
                <div class="col-md-12 m-5 text-center">
                    <p>Data Berhasil Dikirim</p>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('assets/scripts/jquery.min.js') }}"></script>
<script src="{{ asset('assets/scripts/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/scripts/aos.js') }}"></script>
<script src="{{ asset('assets/scripts/multi-countdown.js') }}"></script>
<script src="{{ asset('assets/popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/popup/magnific-popup-options.js') }}"></script>

<script>
    // Variabel untuk menandai apakah audio sudah dimulai
    let audioStarted = false;

    AOS.init({
        duration: 1500,
        delay: 500,
    });

    // Fungsi untuk memulai audio dengan unmute
    function startAudio() {
        if (!audioStarted) {
            var myAudio = document.getElementById("myAudio");
            var playPauseBtn = document.getElementById("playPausebtn");

            try {
                // Unmute audio dan set volume
                myAudio.muted = false;
                myAudio.volume = 0.7;

                // Coba play audio
                const playPromise = myAudio.play();

                if (playPromise !== undefined) {
                    playPromise.then(function () {
                        // Audio berhasil diputar
                        playPauseBtn.src = "{{ asset('assets/images/pause.svg') }}";
                        audioStarted = true;
                        console.log('Audio started successfully');
                    }).catch(function (error) {
                        console.log('Auto-play prevented:', error);
                        setupAudioFallback();
                    });
                }
            } catch (error) {
                console.log('Audio error:', error);
                setupAudioFallback();
            }
        }
    }

    // Setup fallback untuk audio
    function setupAudioFallback() {
        var myAudio = document.getElementById("myAudio");
        var playPauseBtn = document.getElementById("playPausebtn");

        // Tunggu interaksi user
        const startAudioOnInteraction = function () {
            myAudio.muted = false;
            myAudio.volume = 0.7;
            myAudio.play().then(function () {
                playPauseBtn.src = "{{ asset('assets/images/pause.svg') }}";
                audioStarted = true;
            });

            // Hapus event listeners setelah audio dimulai
            document.removeEventListener('click', startAudioOnInteraction);
            document.removeEventListener('scroll', startAudioOnInteraction);
            document.removeEventListener('touchstart', startAudioOnInteraction);
        };

        document.addEventListener('click', startAudioOnInteraction);
        document.addEventListener('scroll', startAudioOnInteraction);
        document.addEventListener('touchstart', startAudioOnInteraction);
    }

    // Fungsi untuk menyembunyikan pop-up
    function hidePlay() {
        var modal = document.getElementById("modal");
        modal.style.opacity = "0";
        modal.style.visibility = "hidden";

        document.getElementById("btn-audio").style.opacity = "1";

        startAudio();

        setTimeout(function () {
            var introSection = document.getElementById("intro");
            if (introSection) {
                introSection.scrollIntoView({behavior: 'smooth'});
            }
        }, 500);
    }

    function Play() {
        var myAudio = document.getElementById("myAudio");
        var playPauseBtn = document.getElementById("playPausebtn");

        if (myAudio.paused) {
            myAudio.play();
            playPauseBtn.src = "{{ asset('assets/images/pause.svg') }}";
        } else {
            myAudio.pause();
            playPauseBtn.src = "{{ asset('assets/images/play.svg') }}";
        }
    }

    // Copy to clipboard function
    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
        alert("Nomor rekening berhasil disalin!");
    }

    // Form submission dengan AJAX
    $(document).ready(function () {
        // Mulai audio secara otomatis ketika halaman dimuat
        setTimeout(function () {
            startAudio();
        }, 500);

        $('#konfirmasi-form').on('submit', function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('wedding.store-message') }}",
                type: "POST",
                data: formData,
                beforeSend: function () {
                    $('#loadingkonfirmasiform').removeClass('invisible');
                },
                success: function (response) {
                    $('#successModal').modal('show');
                    $('#konfirmasi-form')[0].reset();
                    $('#loadingkonfirmasiform').addClass('invisible');

                    // Reload halaman untuk menampilkan pesan terbaru
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                },
                error: function (xhr) {
                    alert('Terjadi kesalahan. Silahkan coba lagi.');
                    $('#loadingkonfirmasiform').addClass('invisible');
                }
            });
        });

        // Inisialisasi carousel
        $('#carousel').carousel({
            interval: 3000,
            pause: 'false'
        });
    });

    // Fallback tambahan ketika window fully loaded
    window.addEventListener('load', function () {
        setTimeout(function () {
            if (!audioStarted) {
                startAudio();
            }
        }, 1000);
    });

    // Auto flip dengan requestAnimationFrame untuk performance lebih baik
    document.addEventListener('DOMContentLoaded', function () {
        const flipElements = [
            {id: 'flipper-groom', interval: null},
            {id: 'flipper-bride', interval: null}
        ];

        // Start auto flip untuk semua element
        flipElements.forEach((element, index) => {
            // Delay start yang berbeda agar tidak bersamaan
            setTimeout(() => {
                element.interval = setInterval(() => {
                    const flipper = document.getElementById(element.id);
                    if (flipper) {
                        flipper.classList.toggle('flipped');
                    }
                }, 5000);
            }, index * 500); // Delay 500ms untuk yang kedua
        });

        window.addEventListener('beforeunload', function () {
            flipElements.forEach(element => {
                if (element.interval) {
                    clearInterval(element.interval);
                }
            });
        });

        const textarea = document.getElementById('pesan-fm');
        const charCount = document.getElementById('char-count');
        const form = document.getElementById('konfirmasi-form');
        const submitBtn = document.getElementById('send-konfirmasi');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');

        // Character counter
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;

            if (count > 240) {
                charCount.style.color = '#e44d26';
            } else {
                charCount.style.color = '#f26161';
            }
        });

        // Form submission loading state
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;

            // Simulate form submission
            setTimeout(() => {
                // Reset button state setelah 2 detik (simulasi)
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
                alert('Konfirmasi berhasil dikirim!');
                form.reset();
                charCount.textContent = '0';
            }, 2000);
        });

        // Enhanced focus effects
        const formControls = document.querySelectorAll('.custom-input, .custom-select, .custom-textarea');

        formControls.forEach(control => {
            control.addEventListener('focus', function() {
                this.style.zIndex = '1000';
            });

            control.addEventListener('blur', function() {
                this.style.zIndex = '1';
            });
        });

        // Center the box on window resize
        function centerBox() {
            const container = document.querySelector('.konfirmasi-container');
            const box = document.querySelector('.konfirmasi-box');

            if (container && box) {
                const containerHeight = container.clientHeight;
                const boxHeight = box.clientHeight;
                const offset = (containerHeight - boxHeight) / 2;

                box.style.marginTop = offset > 0 ? '0' : '20px';
            }
        }

        window.addEventListener('resize', centerBox);
        centerBox(); // Initial center
    });
</script>
</body>
</html>
