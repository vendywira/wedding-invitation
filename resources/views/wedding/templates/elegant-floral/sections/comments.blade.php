<!-- Ucapan Section -->
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">Kiriman Ucapan</div>

<section id="komentar">
    <div class="container komentar-box">
        <div class="container-fluid">
            <div class="col-xs-8 col-sm-6" id="totalDoa-view" data-aos="zoom-in">
                <strong>{{ $messages->count() }} Ucapan</strong>
            </div>
        </div>
        <br>
        <!-- Scrollable Comments Container -->
        <div class="container-fluid comments-scrollable-container" data-aos="zoom-in">
            <div class="comments-scrollable @if($messages->count() <= 5) no-scroll @endif"
                 id="listkomentar"
                 @if($messages->count() > 5) style="max-height: 400px; overflow-y: auto;" @endif>
                @if($messages->count() > 0)
                @foreach($messages as $message)
                <div class="comment-item">
                    <strong>{{ $message->name }}</strong>
                    <small>{{ $message->created_at->format('d M Y H:i') }}</small>
                    <p>{{ $message->message }}</p>
                </div>
                @endforeach
                @else
                <div class="text-center no-comments">
                    <p>Belum ada ucapan. Jadilah yang pertama mengucapkan selamat!</p>
                </div>
                @endif
            </div>

            <!-- Scroll Indicator - Hanya tampil jika lebih dari 5 komentar -->
            @if($messages->count() > 5)
            <div class="scroll-indicator" id="scrollIndicator">
                <i class="fas fa-chevron-down"></i>
                <span>Scroll untuk melihat lebih banyak ucapan</span>
            </div>
            @endif
        </div>
        <div align="center" data-aos="zoom-in-down">
            <button type="button" id="showallcomment-btn" class="btn @if($messages->count() <= 5) invisible @endif"
                    name="submit">
                TAMPILKAN SEMUA ({{ $messages->count() }})
            </button>
        </div>
    </div>
</section>
