@php
    $storyTitle = $template->getSetting('story_title', 'Our Story');
    $storySubtitle = $template->getSetting('story_subtitle', 'Every love story is beautiful but ours is my favorite');
@endphp

<section id="story" class="story-section">
    <div class="story-container">
        <h2 class="story-title muncul">{{ $storyTitle }}</h2>
        <p class="story-subtitle muncul">{{ $storySubtitle }}</p>

        <div class="story-content">
            <div class="story-image muncul">
                <img src="{{ $template->getAssetUrl('story_image', 'assets/vintage/story.jpg') }}" alt="" class="img-fluid">
            </div>

            <div class="story-timeline">
                @if(isset($story_events) && count($story_events) > 0)
                    @foreach($story_events as $index => $story)
                        <div class="story-item muncul">
                            <div class="story-year">{{ $story['year'] }}</div>
                            <h3 class="story-event-title">{{ $story['title'] }}</h3>
                            <p class="story-description">{{ $story['description'] }}</p>
                        </div>
                        @if(!$loop->last)
                            <div class="story-divider"></div>
                        @endif
                    @endforeach
                @else
                    <div class="story-item muncul">
                        <div class="story-year">2017</div>
                        <h3 class="story-event-title">PERTEMUAN</h3>
                        <p class="story-description">Kami pertama kali bertemu sebagai teman kuliah yang sedang menempuh pendidikan Teknik Sipil. Bersama-sama kami mengerjakan tugas besar dan membangun fondasi persahabatan yang kuat.</p>
                    </div>
                    <div class="story-divider"></div>
                    <div class="story-item muncul">
                        <div class="story-year">2026</div>
                        <h3 class="story-event-title">LAMARAN</h3>
                        <p class="story-description">Setelah hubungan jarak jauh yang penuh kesabaran, pada tanggal 28 April 2026, kami memutuskan untuk melangkah ke jenjang yang lebih serius.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
