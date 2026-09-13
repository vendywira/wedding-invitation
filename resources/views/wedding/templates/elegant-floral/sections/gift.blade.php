<!-- Kado Digital Section -->
<br><br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Kado Digital</div>

<section id="gift" class="gift-section">
    <div class="gift-container">
        <p class="gift-subtitle" data-aos="zoom-in">
            Bagi keluarga dan sahabat yang ingin mengirimkan hadiah secara cashless,
            silahkan transfer ke salah satu rekening berikut:
        </p>

        <div class="gift-cards">
            <!-- BCA -->
            <div class="gift-card" data-aos="fade-up" data-aos-delay="100">
                <div class="gift-card-header">
                    <img src="{{ asset('assets/images/bca.png') }}" alt="BCA" class="gift-logo">
                    <h3 class="gift-bank-name">Bank BCA</h3>
                </div>
                <div class="gift-details">
                    <p class="gift-account-name">Nomor Rekening:</p>
                    <div class="gift-account-number" id="bca-account">1420421377</div>
                    <p class="gift-account-name">Atas Nama:</p>
                    <p class="gift-account-holder">I Wayan Vendy Wiranatha</p>
                </div>
                <div class="gift-actions">
                    <button class="gift-copy-btn" onclick="copyToClipboard('bca-account')">
                        <i class="fas fa-copy"></i> Salin Nomor
                    </button>
                </div>
            </div>

            <!-- Jago -->
            <div class="gift-card" data-aos="fade-up" data-aos-delay="200">
                <div class="gift-card-header">
                    <img src="{{ asset('assets/images/jago.jpg') }}" alt="Jago" class="gift-logo">
                    <h3 class="gift-bank-name">Bank Jago</h3>
                </div>
                <div class="gift-details">
                    <p class="gift-account-name">Nomor Rekening:</p>
                    <div class="gift-account-number" id="jago-account">109246172960</div>
                    <p class="gift-account-name">Atas Nama:</p>
                    <p class="gift-account-holder">I Wayan Vendy Wiranatha</p>
                </div>
                <div class="gift-actions">
                    <button class="gift-copy-btn" onclick="copyToClipboard('jago-account')">
                        <i class="fas fa-copy"></i> Salin Nomor
                    </button>
                </div>
            </div>

            <!-- BRI -->
            <div class="gift-card" data-aos="fade-up" data-aos-delay="300">
                <div class="gift-card-header">
                    <img src="{{ asset('assets/images/bri.png') }}" alt="BRI" class="gift-logo">
                    <h3 class="gift-bank-name">Bank BRI</h3>
                </div>
                <div class="gift-details">
                    <p class="gift-account-name">Nomor Rekening:</p>
                    <div class="gift-account-number" id="bri-account">012401062555500</div>
                    <p class="gift-account-name">Atas Nama:</p>
                    <p class="gift-account-holder">Margaretha Magdalena</p>
                </div>
                <div class="gift-actions">
                    <button class="gift-copy-btn" onclick="copyToClipboard('bri-account')">
                        <i class="fas fa-copy"></i> Salin Nomor
                    </button>
                </div>
            </div>
        </div>

        <p class="gift-note" data-aos="zoom-in">
            <i class="fas fa-info-circle"></i>
            Kehadiran dan doa Bapak/Ibu/Saudara/i sudah merupakan kebahagiaan tersendiri bagi kami.
            Apabila berkenan memberikan tanda kasih, kami dapat menerima konfirmasi
            melalui WhatsApp atau form kehadiran.
            Terima kasih atas cinta dan restunya.
        </p>
    </div>
</section>
