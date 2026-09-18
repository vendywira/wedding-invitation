@php
    // Daftar hadiah dikelola di Settings → Hadiah (bank / e-wallet / alamat kado),
    // jadi section ini tidak lagi memakai rekening demo yang di-hardcode.
    $gifts = $giftAccounts ?? [];
@endphp
<!-- Kado Digital Section -->
<br><br>
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">{{ $template->getSetting('gift_title', 'Kado Digital') ?: 'Kado Digital' }}</div>

<section id="gift" class="gift-section">
    <div class="gift-container">
        <p class="gift-subtitle" data-aos="zoom-in">
            {{ $template->getSetting('gift_subtitle', 'Bagi keluarga dan sahabat yang ingin mengirimkan hadiah secara cashless, silahkan transfer ke salah satu rekening berikut:') }}
        </p>

        <div class="gift-cards">
            @forelse($gifts as $gift)
                @php
                    $giftLogoUrl = $template->getGiftLogoUrl($gift);
                    $giftIsAddress = ($gift['type'] ?? 'bank') === 'address';
                    // `id` hadiah bisa sudah berawalan "gift-" (mis. gift-1789577240037),
                    // jadi prefix-nya tidak digandakan.
                    $giftNumberId = 'gift-' . preg_replace('/^gift-/', '', (string) $gift['id']);
                @endphp
                <div class="gift-card" data-aos="fade-up" data-aos-delay="{{ min(500, 100 * $loop->iteration) }}">
                    <div class="gift-card-header">
                        @if($giftIsAddress || ! $giftLogoUrl)
                            <div class="gift-logo gift-logo-icon">
                                <i class="fas fa-gift" aria-hidden="true"></i>
                            </div>
                        @else
                            <img src="{{ $giftLogoUrl }}" alt="{{ $gift['label'] }}" class="gift-logo">
                        @endif
                        <h3 class="gift-bank-name">{{ $gift['label'] }}</h3>
                    </div>
                    <div class="gift-details">
                        @if($giftIsAddress)
                            <p class="gift-account-name">Alamat Pengiriman Kado:</p>
                            <p class="gift-account-holder" id="{{ $giftNumberId }}">{{ $gift['address'] }}</p>
                        @else
                            <p class="gift-account-name">Nomor Rekening:</p>
                            <div class="gift-account-number" id="{{ $giftNumberId }}">{{ $gift['number'] }}</div>
                        @endif

                        @if(($gift['holder'] ?? '') !== '')
                            <p class="gift-account-name">Atas Nama:</p>
                            <p class="gift-account-holder">{{ $gift['holder'] }}</p>
                        @endif
                    </div>
                    <div class="gift-actions">
                        <button type="button" class="gift-copy-btn" onclick="copyToClipboard('{{ $giftNumberId }}')">
                            <i class="fas fa-copy" aria-hidden="true"></i> Salin {{ $giftIsAddress ? 'Alamat' : 'Nomor' }}
                        </button>
                    </div>
                </div>
            @empty
                <p class="gift-note">Rekening kado belum diatur.</p>
            @endforelse
        </div>

        <p class="gift-note" data-aos="zoom-in">
            <i class="fas fa-info-circle" aria-hidden="true"></i>
            Kehadiran dan doa Bapak/Ibu/Saudara/i sudah merupakan kebahagiaan tersendiri bagi kami.
            Apabila berkenan memberikan tanda kasih, kami dapat menerima konfirmasi
            melalui WhatsApp atau form kehadiran.
            Terima kasih atas cinta dan restunya.
        </p>
    </div>
</section>
