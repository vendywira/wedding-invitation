<section id="story" class="story-section">
    <div class="story-container">
        <h2 class="story-title muncul">Our Story</h2>
        <p class="story-subtitle muncul">Every love story is beautiful but ours is my favorite</p>

        <div class="story-content">
            <div class="story-image muncul">
                <img src="{{ asset($template->getAsset('story_image', 'assets/vintage/story.jpg')) }}" alt="" class="img-fluid">
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
