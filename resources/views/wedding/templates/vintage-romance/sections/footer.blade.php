<section id="footer" class="footer-section">
    <div class="footer-particles" id="footer-particles"></div>
    <div class="footer-container">
        <div class="footer-image muncul">
            <img src="{{ asset($template->getAsset('story_image', 'assets/vintage/story.jpg')) }}" alt="" class="img-fluid">
        </div>
        <h2 class="footer-thanks muncul">Terima Kasih</h2>
        <p class="footer-message muncul">
            Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kami.
        </p>
        <h3 class="footer-couple muncul">{{ $guestData->couple_name ?? 'Isabel & Jefry' }}</h3>
        <div class="footer-decorations">
            <img src="{{ asset($template->getAsset('flower_decoration', 'assets/vintage/flower.png')) }}" class="footer-flower footer-flower-left goyang-1" alt="">
            <img src="{{ asset($template->getAsset('flower_decoration', 'assets/vintage/flower.png')) }}" class="footer-flower footer-flower-right goyang-1" alt="">
        </div>
        <p class="footer-wassalam muncul">Wassalamu'alaikum Warahmatullahi Wabarakatuh</p>
    </div>
</section>
