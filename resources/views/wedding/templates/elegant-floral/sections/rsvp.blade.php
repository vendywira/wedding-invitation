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
                    <input type="hidden" name="guest_code" value="{{ $guestData->code }}">
                    <input type="hidden" name="guest_id" value="{{ $guestData->id }}">

                    <div class="text-center mb-4">
                        <h3 class="form-title">Konfirmasi Kehadiran</h3>
                        <p class="form-subtitle">Dimohon kesediaannya untuk mengisi form kehadiran undangan kami</p>
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
                            <select class="form-control custom-select" name="guest_attends" id="jumlah-fm" required>
                                <option value="" disabled selected>Pilih Jumlah Tamu</option>
                                <option value="1">1 Orang</option>
                                <option value="2">2 Orang</option>
                                <option value="3">3 Orang</option>
                                <option value="4">4 Orang</option>
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
