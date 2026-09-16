@php
    $footerMessage = $template->getSetting('footer_message', 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kami.');
    $wassalamText = $template->getSetting('wassalam_text', "Wassalamu'alaikum Warahmatullahi Wabarakatuh");
    $coupleName = $guestData->couple_name ?? ($template->getSetting('bride_name', 'Isabel') . ' & ' . $template->getSetting('groom_name', 'Jefry'));
@endphp

<section id="footer" class="footer-section">
    <div class="footer-particles" id="footer-particles"></div>
    <div class="footer-container">
        <div class="footer-image muncul">
            <img src="{{ $template->getAssetUrl('story_image', 'assets/vintage/story.jpg') }}" alt="" class="img-fluid">
        </div>
        <h2 class="footer-thanks muncul">Terima Kasih</h2>
        <p class="footer-message muncul">
            {{ $footerMessage }}
        </p>
        <h3 class="footer-couple muncul">{{ $coupleName }}</h3>
        <div class="footer-decorations">
            <img src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/flower.png') }}" class="footer-flower footer-flower-left goyang-1" alt="">
            <img src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/flower.png') }}" class="footer-flower footer-flower-right goyang-1" alt="">
        </div>
        <p class="footer-wassalam muncul">{{ $wassalamText }}</p>
    </div>
</section>
