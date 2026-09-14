document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 1200,
        delay: 300,
        once: true,
        easing: 'ease-out-cubic'
    });

    const audio = document.getElementById('myAudio');
    const btnAudio = document.getElementById('btn-audio');
    let isPlaying = false;

    function startAudio() {
        if (audio) {
            audio.muted = false;
            audio.volume = 0.5;
            audio.play().then(() => {
                isPlaying = true;
                if (btnAudio) btnAudio.classList.add('playing');
            }).catch(e => {
                console.log('Audio autoplay blocked:', e);
            });
        }
    }

    function toggleAudio() {
        if (isPlaying) {
            audio.pause();
            isPlaying = false;
            if (btnAudio) btnAudio.classList.remove('playing');
        } else {
            startAudio();
        }
    }

    if (btnAudio) {
        btnAudio.addEventListener('click', toggleAudio);
    }

    document.addEventListener('visibilitychange', function() {
        if (audio) {
            if (document.hidden) {
                audio.pause();
            } else if (isPlaying) {
                audio.play().catch(() => {});
            }
        }
    });

    const modal = document.getElementById('modal');
    const openBtn = document.getElementById('open-invitation');

    if (openBtn && modal) {
        document.body.classList.add('no-scroll');

        openBtn.addEventListener('click', function() {
            modal.classList.add('removeModals');
            document.body.classList.remove('no-scroll');
            document.body.style.overflow = 'auto';
            startAudio();

            setTimeout(() => {
                const hero = document.getElementById('hero');
                if (hero) {
                    hero.scrollIntoView({ behavior: 'smooth' });
                }
            }, 500);
        });
    }

    if (typeof particlesJS !== 'undefined') {
        const heroParticles = document.getElementById('hero-particles');
        if (heroParticles) {
            particlesJS('hero-particles', {
                "particles": {
                    "number": { "value": 491, "density": { "enable": true, "value_area": 6012.795228245711 } },
                    "color": { "value": "#ffffff" },
                    "shape": { "type": "circle", "stroke": { "width": 0, "color": "#000000" }, "polygon": { "nb_sides": 3 } },
                    "opacity": { "value": 0.5524033491425908, "random": true, "anim": { "enable": false, "speed": 1, "opacity_min": 0.1, "sync": false } },
                    "size": { "value": 10, "random": true, "anim": { "enable": true, "speed": 17, "size_min": 0.1, "sync": false } },
                    "line_linked": { "enable": false, "distance": 150, "color": "#ffffff", "opacity": 0.4, "width": 1 },
                    "move": { "enable": true, "speed": 5, "direction": "bottom", "random": false, "straight": false, "out_mode": "out", "bounce": false, "attract": { "enable": true, "rotateX": 2130.6986324071363, "rotateY": 4498.141557303954 } }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": { "enable": true, "mode": "repulse" },
                        "onclick": { "enable": true, "mode": "push" },
                        "resize": true
                    },
                    "modes": {
                        "grab": { "distance": 400, "line_linked": { "opacity": 1 } },
                        "bubble": { "distance": 400, "size": 40, "duration": 2, "opacity": 8, "speed": 3 },
                        "repulse": { "distance": 276.1062521824573, "duration": 0.4 },
                        "push": { "particles_nb": 4 },
                        "remove": { "particles_nb": 2 }
                    }
                },
                "retina_detect": true
            });
        }

        const footerParticles = document.getElementById('footer-particles');
        if (footerParticles) {
            particlesJS('footer-particles', {
                "particles": {
                    "number": { "value": 33, "density": { "enable": true, "value_area": 2051.7838682439087 } },
                    "color": { "value": "#ffffff" },
                    "shape": { "type": "polygon", "polygon": { "nb_sides": 5 } },
                    "opacity": { "value": 0.7776548495197786, "random": true },
                    "size": { "value": 98.64345520403408, "random": true, "anim": { "enable": true, "speed": 31.67101127975246, "size_min": 11.369080972218832, "sync": true } },
                    "line_linked": { "enable": false },
                    "move": { "enable": true, "speed": 6.413648243462092, "direction": "bottom-right", "random": false, "straight": false, "out_mode": "out", "attract": { "enable": true, "rotateX": 481.0236182596568, "rotateY": 561.194221302933 } }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": { "onhover": { "enable": false }, "onclick": { "enable": false }, "resize": true }
                },
                "retina_detect": true
            });
        }
    }

    const countdownTimer = document.querySelector('.countdown-timer');
    if (countdownTimer) {
        const targetDate = new Date(countdownTimer.dataset.date).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.querySelector('.days').textContent = String(days).padStart(2, '0');
                document.querySelector('.hours').textContent = String(hours).padStart(2, '0');
                document.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
                document.querySelector('.seconds').textContent = String(seconds).padStart(2, '0');
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    const slides = document.querySelectorAll('.countdown-slide');
    if (slides.length > 1) {
        let currentSlide = 0;
        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 5000);
    }

    window.copyToClipboard = function(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                showCopyToast();
            }).catch(() => {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    };

    function fallbackCopy(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.cssText = 'position:fixed;left:-9999px;';
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showCopyToast();
        } catch (e) {
            console.error('Copy failed:', e);
        }
        document.body.removeChild(textArea);
    }

    function showCopyToast() {
        const existing = document.querySelector('.copy-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = 'copy-toast';
        toast.textContent = 'Copied!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }

    const form = document.getElementById('konfirmasi-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const btnText = form.querySelector('.btn-text');
            const btnLoading = form.querySelector('.btn-loading');

            if (btnText) btnText.classList.add('d-none');
            if (btnLoading) btnLoading.classList.remove('d-none');

            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                             document.querySelector('input[name="_token"]')?.value;

            fetch('/store-message', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (btnText) btnText.classList.remove('d-none');
                if (btnLoading) btnLoading.classList.add('d-none');

                if (data.success) {
                    const successModal = document.getElementById('successModal');
                    if (successModal) {
                        const modal = new bootstrap.Modal(successModal);
                        modal.show();
                    }
                    form.reset();
                    if (data.messages) {
                        updateMessages(data.messages);
                    }
                }
            })
            .catch(error => {
                if (btnText) btnText.classList.remove('d-none');
                if (btnLoading) btnLoading.classList.add('d-none');
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        });
    }

    function updateMessages(messages) {
        const container = document.getElementById('comments-list');
        if (!container || !messages) return;

        container.innerHTML = '';

        if (messages.length === 0) {
            container.innerHTML = '<p class="no-comments">Belum ada ucapan. Jadilah yang pertama!</p>';
            return;
        }

        messages.forEach(msg => {
            const div = document.createElement('div');
            div.className = 'comment-item';
            div.innerHTML = `
                <div class="comment-avatar">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(msg.name)}&background=d4a853&color=1a1a1a&bold=true" alt="">
                </div>
                <div class="comment-content">
                    <h5 class="comment-author">${escapeHtml(msg.name)}</h5>
                    <span class="comment-time">${formatTime(msg.created_at)}</span>
                    <p class="comment-text">${escapeHtml(msg.message)}</p>
                </div>
            `;
            container.appendChild(div);
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatTime(dateStr) {
        const date = new Date(dateStr);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return 'Baru saja';
        if (minutes < 60) return `${minutes} menit yang lalu`;
        if (hours < 24) return `${hours} jam yang lalu`;
        if (days < 7) return `${days} hari yang lalu`;
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    const messageField = document.getElementById('message');
    const charCount = document.getElementById('char-count');
    if (messageField && charCount) {
        messageField.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            if (count > 240) {
                charCount.style.color = '#ef4444';
            } else {
                charCount.style.color = '';
            }
        });
    }

    if (typeof $.fn.magnificPopup !== 'undefined') {
        $('.gallery-item').magnificPopup({
            type: 'image',
            gallery: { enabled: true },
            zoom: {
                enabled: true,
                duration: 300
            }
        });
    }

    const revealElements = document.querySelectorAll('.muncul, .zoom');
    if (revealElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        });

        revealElements.forEach(el => observer.observe(el));
    }

    document.querySelectorAll('.gift-card-header').forEach(header => {
        header.addEventListener('click', function() {
            const card = this.closest('.gift-card');
            const body = card.querySelector('.gift-card-body');
            const isOpen = body.classList.contains('open');

            document.querySelectorAll('.gift-card-body').forEach(b => {
                b.classList.remove('open');
            });

            if (!isOpen) {
                body.classList.add('open');
            }
        });
    });

    if (typeof Swiper !== 'undefined') {
        new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    }

    function updateStats() {}

    updateStats();
});
