@php
    $brideName = $template->getSetting('bride_name', 'Isabel');
    $groomName = $template->getSetting('groom_name', 'Jefry');
    $brideFullName = $template->getSetting('bride_full_name', 'Isabela Sofia, S.T.');
    $groomFullName = $template->getSetting('groom_full_name', 'Jefry Alatas, S.T.');
    $brideFather = $template->getSetting('bride_father', 'Abdul Kabir');
    $brideMother = $template->getSetting('bride_mother', 'Masniawati');
    $groomFather = $template->getSetting('groom_father', "Ba'dulu Alatas");
    $groomMother = $template->getSetting('groom_mother', 'Halisah Herawati');
    $brideInstagram = $template->getSetting('bride_instagram', '#');
    $groomInstagram = $template->getSetting('groom_instagram', '#');
    $coupleSubtitle = $template->getSetting('couple_subtitle', 'Bride & Groom');
@endphp

<section id="couple" class="couple-section">
    <div class="couple-particles" id="couple-particles"></div>
    <div class="couple-container">
        <div class="couple-header muncul">
            <img src="{{ $template->getAssetUrl('logo_image', 'assets/vintage/logo.png') }}" alt="Logo" class="couple-logo">
            <h2 class="couple-title">{{ $coupleSubtitle }}</h2>
        </div>

        <div class="couple-opening muncul">
            <p>Assalamu'alaikum Warahmatullahi Wabarakatuh</p>
            <p>Dengan memohon rahmat dan ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami:</p>
        </div>

        <div class="couple-grid">
            <div class="couple-person muncul">
                <div class="couple-photo-wrapper">
                    <img src="{{ $template->getAssetUrl('bride_photo', 'assets/vintage/bride.jpg') }}" alt="Bride" class="couple-photo">
                    <img src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/flower.png') }}" class="couple-flower couple-flower-left goyang-1" alt="">
                </div>
                <h3 class="couple-name">{{ $brideFullName }}</h3>
                <p class="couple-parents">Putri dari Bapak {{ $brideFather }} & Ibu {{ $brideMother }}</p>
                <a href="{{ $brideInstagram }}" class="couple-instagram" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>

            <div class="couple-ampersand muncul">
                <span>&</span>
            </div>

            <div class="couple-person muncul">
                <div class="couple-photo-wrapper">
                    <img src="{{ $template->getAssetUrl('groom_photo', 'assets/vintage/groom.jpg') }}" alt="Groom" class="couple-photo">
                    <img src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/flower.png') }}" class="couple-flower couple-flower-right goyang-1" alt="">
                </div>
                <h3 class="couple-name">{{ $groomFullName }}</h3>
                <p class="couple-parents">Putra dari Bapak {{ $groomFather }} & Ibu {{ $groomMother }}</p>
                <a href="{{ $groomInstagram }}" class="couple-instagram" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>

        <div class="couple-date muncul">
            <p class="couple-save-label">SAVE THE DATE</p>
            <img src="{{ $template->getAssetUrl('lamp_decoration', 'assets/vintage/lamp.png') }}" class="couple-lamp goyang-2" alt="">
        </div>
    </div>
</section>
