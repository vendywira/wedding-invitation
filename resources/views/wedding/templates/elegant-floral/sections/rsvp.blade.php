@php
    $savedAttends = (int) ($guestData->guest_attends ?? 1);
    $savedAttends = $savedAttends >= 1 && $savedAttends <= 4 ? $savedAttends : 1;
    $savedAttendance = $guestData->attendance ?? '';
@endphp
<!-- Konfirmasi Tamu Section -->
<br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">{{ $template->getSetting('rsvp_title', 'Konfirmasi Tamu') }}</div>

<section id="konfirmasi" data-aos="zoom-in">
    <div class="konfirmasi-container">
        <div class="konfirmasi-wrapper">
            <div class="konfirmasi-box">
                <form id="konfirmasi-form" method="POST" autocomplete="off" class="form-group">
                    @csrf
                    <input type="hidden" name="guest_code" value="{{ $guestData->code }}">
                    <input type="hidden" name="guest_id" value="{{ $guestData->id }}">

                    <div class="text-center mb-4">
                        <h3 class="form-title">Konfirmasi Kehadiran</h3>
                        <p class="form-subtitle">{{ $template->getSetting('rsvp_subtitle', 'Dimohon kesediaannya untuk mengisi form kehadiran undangan kami') }}</p>
                    </div>

                    <div class="form-group-row">
                        <div class="col-12">
                            <input type="text" class="form-control custom-input" name="name" id="nama-fm"
                                   placeholder="Nama Lengkap" required
                                   value="{{ $guestData->name ?? request()->get('to') }}">
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="col-12">
                            <select class="form-control custom-select" name="attendance" id="kehadiran-fm" required>
                                <option value="" disabled @if($savedAttendance === '') selected @endif>Konfirmasi Kehadiran</option>
                                <option value="Hadir" @if($savedAttendance === 'Hadir') selected @endif>Iya, Saya Hadir</option>
                                <option value="Tidak Hadir" @if($savedAttendance === 'Tidak Hadir') selected @endif>Maaf, Saya Tidak Hadir</option>
                            </select>
                        </div>
                    </div>

                    {{-- Jumlah tamu hanya relevan kalau hadir. Nilainya sengaja tidak
                         dikosongkan saat disembunyikan supaya `guest_attends` tetap
                         terkirim (server memvalidasinya sebagai integer 1-10). --}}
                    <div class="form-group-row" id="jumlah-tamu-row">
                        <div class="col-12">
                            <select class="form-control custom-select" name="guest_attends" id="jumlah-fm">
                                <option value="1" @if($savedAttends === 1) selected @endif>1 Orang</option>
                                <option value="2" @if($savedAttends === 2) selected @endif>2 Orang</option>
                                <option value="3" @if($savedAttends === 3) selected @endif>3 Orang</option>
                                <option value="4" @if($savedAttends === 4) selected @endif>4 Orang</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="col-12">
                            <textarea class="form-control custom-textarea" maxlength="250" name="message" id="pesan-fm"
                                      rows="3"
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

                    {{-- Pesan error/status dari server ditampilkan di sini (bukan
                         lewat alert generik) supaya tamu tahu persis kenapa
                         konfirmasinya ditolak. Diisi oleh template.js. --}}
                    <div class="konfirmasi-status" id="konfirmasi-status" role="status" aria-live="polite"></div>
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

<style>
    /* Pesan status konfirmasi: hanya tampil saat diisi oleh template.js. */
    .konfirmasi-status {
        display: none;
        margin-top: 14px;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.9rem;
        line-height: 1.45;
        text-align: center;
    }

    .konfirmasi-status.is-visible {
        display: block;
    }

    .konfirmasi-status.is-error {
        background: rgba(200, 89, 81, 0.12);
        border: 1px solid rgba(200, 89, 81, 0.45);
        color: #b23b34;
    }

    .konfirmasi-status.is-success {
        background: rgba(49, 147, 66, 0.12);
        border: 1px solid rgba(49, 147, 66, 0.45);
        color: #2f7d3d;
    }
</style>

<script>
    // Pilihan jumlah tamu muncul hanya saat memilih "Iya, Saya Hadir".
    (function () {
        var attendance = document.getElementById('kehadiran-fm');
        var row = document.getElementById('jumlah-tamu-row');
        var jumlah = document.getElementById('jumlah-fm');

        if (!attendance || !row || !jumlah) {
            return;
        }

        function syncJumlahTamu() {
            var hadir = attendance.value === 'Hadir';
            row.style.display = hadir ? '' : 'none';
            // `required` hanya saat terlihat: field tersembunyi yang required
            // membuat browser menolak submit tanpa pesan yang jelas.
            jumlah.required = hadir;
        }

        attendance.addEventListener('change', syncJumlahTamu);
        syncJumlahTamu();
    })();
</script>
