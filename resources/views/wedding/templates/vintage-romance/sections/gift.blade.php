<section id="gift" class="gift-section">
    <div class="gift-container muncul">
        <div class="gift-icon">
            <i class="fas fa-gift"></i>
        </div>
        <h2 class="gift-title">Wedding Gift</h2>
        <p class="gift-subtitle">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika Bapak/Ibu ingin memberikan tanda kasih, kami telah menyediakan fitur berikut:</p>

        <div class="gift-accordion">
            @if($template->getAsset('bank_bni_number'))
                <div class="gift-card">
                    <div class="gift-card-header">
                        <img src="{{ asset('assets/images/bni.png') }}" alt="BNI" class="gift-bank-logo">
                        <span class="gift-bank-name">Bank BNI</span>
                    </div>
                    <div class="gift-card-body">
                        <p class="gift-account-number">{{ $template->getAsset('bank_bni_number') }}</p>
                        <p class="gift-account-name">a.n {{ $template->getAsset('bank_bni_name') }}</p>
                        <button class="gift-copy-btn" onclick="copyToClipboard('{{ $template->getAsset('bank_bni_number') }}')">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
            @endif

            @if($template->getAsset('bank_bri_number'))
                <div class="gift-card">
                    <div class="gift-card-header">
                        <img src="{{ asset('assets/images/bri.png') }}" alt="BRI" class="gift-bank-logo">
                        <span class="gift-bank-name">Bank BRI</span>
                    </div>
                    <div class="gift-card-body">
                        <p class="gift-account-number">{{ $template->getAsset('bank_bri_number') }}</p>
                        <p class="gift-account-name">a.n {{ $template->getAsset('bank_bri_name') }}</p>
                        <button class="gift-copy-btn" onclick="copyToClipboard('{{ $template->getAsset('bank_bri_number') }}')">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
            @endif

            @if($template->getAsset('physical_gift_address'))
                <div class="gift-card">
                    <div class="gift-card-header">
                        <i class="fas fa-gift gift-icon-physical"></i>
                        <span class="gift-bank-name">Kirim Hadiah</span>
                    </div>
                    <div class="gift-card-body">
                        <p class="gift-address">{{ $template->getAsset('physical_gift_address') }}</p>
                        <button class="gift-copy-btn" onclick="copyToClipboard('{{ $template->getAsset('physical_gift_address') }}')">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
