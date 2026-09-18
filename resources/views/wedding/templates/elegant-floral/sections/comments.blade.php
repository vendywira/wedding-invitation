@php
    // 5 ucapan terbaru dirender dari server; sisanya diambil bertahap lewat
    // GET /messages saat tamu men-scroll atau menekan "Tampilkan semua".
    $visibleMessages = $messages->take(5);
    $totalMessages = $messages->count();
@endphp
<!-- Ucapan Section -->
<div class="text-center">
    <img src="{{ asset('assets/images/tittle-section.png') }}" width="134" height="23" data-aos="zoom-in-down">
</div>
<div class="tittle-section" data-aos="zoom-in-down">{{ $template->getSetting('best_wishes_title', 'Kiriman Ucapan') }}</div>

<section id="komentar">
    <div class="container komentar-box">
        <div class="container-fluid">
            <div class="col-xs-8 col-sm-6" id="totalDoa-view" data-aos="zoom-in">
                <strong>{{ $totalMessages }} Ucapan</strong>
            </div>
        </div>
        <br>
        <!-- Scrollable Comments Container -->
        <div class="container-fluid comments-scrollable-container" data-aos="zoom-in">
            <div class="comments-scrollable @if($totalMessages <= 5) no-scroll @endif"
                 id="listkomentar"
                 data-total="{{ $totalMessages }}"
                 @if($totalMessages > 5) style="max-height: 400px; overflow-y: auto;" @endif>
                @forelse($visibleMessages as $message)
                    <div class="comment-item">
                        <strong>{{ $message->name }}</strong>
                        <small>{{ $message->created_at?->locale('id')->translatedFormat('d M Y H:i') }}</small>
                        <p>{{ $message->message }}</p>
                    </div>
                @empty
                    <div class="text-center no-comments">
                        <p>Belum ada ucapan. Jadilah yang pertama mengucapkan selamat!</p>
                    </div>
                @endforelse
            </div>

            <!-- Scroll Indicator - Hanya tampil jika lebih dari 5 komentar -->
            @if($totalMessages > 5)
                <div class="scroll-indicator" id="scrollIndicator">
                    <i class="fas fa-chevron-down"></i>
                    <span>Scroll untuk melihat lebih banyak ucapan</span>
                </div>
            @endif
        </div>
        <div align="center" data-aos="zoom-in-down">
            <button type="button" id="showallcomment-btn" class="btn @if($totalMessages <= 5) invisible @endif"
                    name="submit">
                TAMPILKAN SEMUA ({{ $totalMessages }})
            </button>
        </div>
    </div>
</section>

<script>
    // Ambil sisa ucapan bertahap supaya halaman tidak berat sejak awal.
    (function () {
        var container = document.getElementById('listkomentar');
        var indicator = document.getElementById('scrollIndicator');
        var showAllBtn = document.getElementById('showallcomment-btn');

        if (!container) {
            return;
        }

        var loading = false;
        var total = parseInt(container.dataset.total || '0', 10) || 0;

        function escapeHtml(text) {
            var div = document.createElement('div');
            div.textContent = text == null ? '' : text;
            return div.innerHTML;
        }

        function removeEmptyState() {
            var empty = container.querySelector('.no-comments');
            if (empty) {
                empty.remove();
            }
        }

        function appendMessages(messages) {
            removeEmptyState();

            messages.forEach(function (message) {
                var item = document.createElement('div');
                item.className = 'comment-item';
                item.innerHTML = '<strong>' + escapeHtml(message.name) + '</strong>' +
                    '<small>' + escapeHtml(message.date || '') + '</small>' +
                    '<p>' + escapeHtml(message.message || '') + '</p>';
                container.appendChild(item);
            });
        }

        function loadedCount() {
            return container.querySelectorAll('.comment-item').length;
        }

        function finish() {
            if (indicator) {
                indicator.style.display = 'none';
            }
            if (showAllBtn) {
                showAllBtn.classList.add('invisible');
            }
            container.classList.remove('scroll-enabled');
            container.classList.add('no-scroll');
            container.style.maxHeight = 'none';
            container.style.overflowY = 'visible';
        }

        function loadMore(limit) {
            if (loading) {
                return Promise.resolve(false);
            }

            var offset = loadedCount();
            if (total && offset >= total) {
                finish();
                return Promise.resolve(false);
            }

            loading = true;

            return fetch('{{ url('/messages') }}?offset=' + offset + '&limit=' + (limit || 5), {
                headers: {'Accept': 'application/json'}
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    total = parseInt(data.total || '0', 10) || total;
                    appendMessages(data.messages || []);

                    if (loadedCount() >= total) {
                        finish();
                    }

                    return (data.messages || []).length > 0;
                })
                .catch(function () {
                    if (indicator) {
                        indicator.querySelector('span').textContent = 'Gagal memuat ucapan, coba scroll lagi';
                    }
                    return false;
                })
                .then(function (result) {
                    loading = false;
                    return result;
                });
        }

        container.addEventListener('scroll', function () {
            if (container.scrollTop + container.clientHeight >= container.scrollHeight - 40) {
                loadMore(5);
            }
        });

        if (showAllBtn) {
            showAllBtn.addEventListener('click', function () {
                (function loadUntilDone() {
                    loadMore(50).then(function (more) {
                        if (more) {
                            loadUntilDone();
                        } else {
                            finish();
                        }
                    });
                })();
            });
        }
    })();
</script>
