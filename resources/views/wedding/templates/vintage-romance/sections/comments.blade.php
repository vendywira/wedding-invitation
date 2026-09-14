<section id="comments" class="comments-section">
    <div class="comments-container">
        <img src="{{ $template->getAsset('logo_image', asset('assets/vintage/logo.svg')) }}" alt="Logo" class="comments-logo muncul">
        <h2 class="comments-title muncul">Best Wishes</h2>
        <p class="comments-subtitle muncul">Happily ever after notes for the newly weds</p>

        <div class="comments-stats muncul">
            <div class="comment-stat stat-hadir">
                <span class="stat-count" id="stat-hadir">0</span>
                <span class="stat-label">Hadir</span>
            </div>
            <div class="comment-stat stat-tidak-hadir">
                <span class="stat-count" id="stat-tidak-hadir">0</span>
                <span class="stat-label">Tidak Hadir</span>
            </div>
            <div class="comment-stat stat-ragu">
                <span class="stat-count" id="stat-ragu">0</span>
                <span class="stat-label">Masih Ragu</span>
            </div>
        </div>

        <div class="comments-list" id="comments-list">
            @if(isset($messages) && count($messages) > 0)
                @foreach($messages as $message)
                    <div class="comment-item muncul">
                        <div class="comment-avatar">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->name) }}&background=d4a853&color=1a1a1a&bold=true" alt="">
                        </div>
                        <div class="comment-content">
                            <h5 class="comment-author">{{ $message->name }}</h5>
                            <span class="comment-time">{{ $message->created_at->diffForHumans() }}</span>
                            <p class="comment-text">{{ $message->message }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="no-comments">Belum ada ucapan. Jadilah yang pertama!</p>
            @endif
        </div>
    </div>
</section>
