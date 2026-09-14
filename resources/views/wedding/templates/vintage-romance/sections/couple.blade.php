<section id="couple" class="couple-section">
    <div class="couple-particles" id="couple-particles"></div>
    <div class="couple-container">
        <div class="couple-header muncul">
            <img src="{{ asset($template->getAsset('logo_image', 'assets/vintage/logo.png')) }}" alt="Logo" class="couple-logo">
            <h2 class="couple-title">Bride & Groom</h2>
        </div>

        <div class="couple-opening muncul">
            <p>Assalamu'alaikum Warahmatullahi Wabarakatuh</p>
            <p>Dengan memohon rahmat dan ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami:</p>
        </div>

        <div class="couple-grid">
            <div class="couple-person muncul">
                <div class="couple-photo-wrapper">
                    <img src="{{ asset($template->getAsset('bride_photo', 'assets/vintage/bride.jpg')) }}" alt="Bride" class="couple-photo">
                    <img src="{{ asset($template->getAsset('flower_decoration', 'assets/vintage/flower.png')) }}" class="couple-flower couple-flower-left goyang-1" alt="">
                </div>
                <h3 class="couple-name">{{ $guestData->bride_name ?? 'Isabela Sofia, S.T.' }}</h3>
                <p class="couple-parents">Putri dari Bapak {{ $guestData->bride_father ?? 'Abdul Kabir' }} & Ibu {{ $guestData->bride_mother ?? 'Masniawati' }}</p>
                <a href="{{ $guestData->bride_instagram ?? '#' }}" class="couple-instagram" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>

            <div class="couple-ampersand muncul">
                <span>&</span>
            </div>

            <div class="couple-person muncul">
                <div class="couple-photo-wrapper">
                    <img src="{{ asset($template->getAsset('groom_photo', 'assets/vintage/groom.jpg')) }}" alt="Groom" class="couple-photo">
                    <img src="{{ asset($template->getAsset('flower_decoration', 'assets/vintage/flower.png')) }}" class="couple-flower couple-flower-right goyang-1" alt="">
                </div>
                <h3 class="couple-name">{{ $guestData->groom_name ?? 'Jefry Alatas, S.T.' }}</h3>
                <p class="couple-parents">Putra dari Bapak {{ $guestData->groom_father ?? "Ba'dulu Alatas" }} & Ibu {{ $guestData->groom_mother ?? 'Halisah Herawati' }}</p>
                <a href="{{ $guestData->groom_instagram ?? '#' }}" class="couple-instagram" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>

        <div class="couple-date muncul">
            <p class="couple-save-label">SAVE THE DATE</p>
            <img src="{{ asset($template->getAsset('lamp_decoration', 'assets/vintage/lamp.png')) }}" class="couple-lamp goyang-2" alt="">
        </div>
    </div>
</section>
