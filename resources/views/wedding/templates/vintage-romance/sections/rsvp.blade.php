@php
    $rsvpTitle = $template->getSetting('rsvp_title', 'Konfirmasi Kehadiran');
    $rsvpSubtitle = $template->getSetting('rsvp_subtitle', 'Mohon konfirmasi kehadiran Bapak/Ibu/Saudara/i');
@endphp

<section id="rsvp" class="rsvp-section">
    <div class="rsvp-container">
        <h2 class="rsvp-title" data-aos="zoom-in">{{ $rsvpTitle }}</h2>
        <p class="rsvp-subtitle" data-aos="fade-up">{{ $rsvpSubtitle }}</p>

        <form id="konfirmasi-form" class="rsvp-form" data-aos="fade-up">
            @csrf
            <input type="hidden" name="guest_code" value="{{ $guestData->code ?? '' }}">
            <input type="hidden" name="guest_id" value="{{ $guestData->id ?? '' }}">

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $guestData->name ?? '' }}" required>
            </div>

            <div class="form-group">
                <label for="attendance">Konfirmasi Kehadiran</label>
                <select id="attendance" name="attendance" class="form-control" required>
                    <option value="">Pilih</option>
                    <option value="hadir">Hadir</option>
                    <option value="tidak_hadir">Tidak Hadir</option>
                    <option value="masih_ragu">Masih Ragu</option>
                </select>
            </div>

            <div class="form-group">
                <label for="guest_attends">Jumlah Tamu</label>
                <select id="guest_attends" name="guest_attends" class="form-control">
                    <option value="1">1 Orang</option>
                    <option value="2">2 Orang</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Ucapan & Doa</label>
                <textarea id="message" name="message" class="form-control" rows="4" maxlength="280" placeholder="Tulis ucapan dan doa untuk kedua mempelai..."></textarea>
                <span class="char-count"><span id="char-count">0</span>/280</span>
            </div>

            <button type="submit" class="btn-submit">
                <span class="btn-text">Kirim</span>
                <span class="btn-loading d-none"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
        </form>

        <div id="successModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h5>Terima Kasih!</h5>
                        <p>Ucapan dan doa Anda telah terkirim.</p>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
