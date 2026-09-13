// Wedding Template JS - Elegant Floral
let audioStarted = false;

AOS.init({
    duration: 1500,
    delay: 500,
});

// Audio functions
function startAudio() {
    if (!audioStarted) {
        var myAudio = document.getElementById("myAudio");
        var playPauseBtn = document.getElementById("playPausebtn");

        try {
            myAudio.muted = false;
            myAudio.volume = 0.7;

            const playPromise = myAudio.play();

            if (playPromise !== undefined) {
                playPromise.then(function () {
                    playPauseBtn.src = "assets/images/pause.svg";
                    audioStarted = true;
                    console.log('Audio started successfully');
                }).catch(function (error) {
                    console.log('Auto-play prevented:', error);
                    setupAudioFallback();
                });
            }
        } catch (error) {
            console.log('Audio error:', error);
            setupAudioFallback();
        }
    }
}

function setupAudioFallback() {
    var myAudio = document.getElementById("myAudio");
    var playPauseBtn = document.getElementById("playPausebtn");

    const startAudioOnInteraction = function () {
        myAudio.muted = false;
        myAudio.volume = 0.7;
        myAudio.play().then(function () {
            playPauseBtn.src = "assets/images/pause.svg";
            audioStarted = true;
        });

        document.removeEventListener('click', startAudioOnInteraction);
        document.removeEventListener('scroll', startAudioOnInteraction);
        document.removeEventListener('touchstart', startAudioOnInteraction);
    };

    document.addEventListener('click', startAudioOnInteraction);
    document.addEventListener('scroll', startAudioOnInteraction);
    document.addEventListener('touchstart', startAudioOnInteraction);
}

function hidePlay() {
    var modal = document.getElementById("modal");
    const video = document.getElementById('popupVideo');

    if (video) {
        video.pause();
    }

    modal.style.opacity = "0";
    modal.style.visibility = "hidden";

    document.getElementById("btn-audio").style.opacity = "1";
    startAudio();

    setTimeout(function () {
        var introSection = document.getElementById("intro");
        if (introSection) {
            introSection.scrollIntoView({behavior: 'smooth'});
        }
    }, 500);
}

function Play() {
    var myAudio = document.getElementById("myAudio");
    var playPauseBtn = document.getElementById("playPausebtn");

    if (myAudio.paused) {
        myAudio.play();
        playPauseBtn.src = "assets/images/pause.svg";
    } else {
        myAudio.pause();
        playPauseBtn.src = "assets/images/play.svg";
    }
}

// Copy to clipboard
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.innerText;

    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);

    tempInput.select();
    tempInput.setSelectionRange(0, 99999);

    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopyToast();
        } else {
            fallbackCopyText(text);
        }
    } catch (err) {
        fallbackCopyText(text);
    }

    document.body.removeChild(tempInput);
}

async function fallbackCopyText(text) {
    try {
        await navigator.clipboard.writeText(text);
        showCopyToast();
    } catch (err) {
        console.error('Failed to copy: ', err);
        alert('Gagal menyalin nomor rekening. Silakan salin manual: ' + text);
    }
}

function showCopyToast() {
    const toast = document.getElementById('copyToast');
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// iOS detection
function isIOS() {
    return [
            'iPad Simulator',
            'iPhone Simulator',
            'iPod Simulator',
            'iPad',
            'iPhone',
            'iPod'
        ].includes(navigator.platform) ||
        (navigator.userAgent.includes("Mac") && "ontouchend" in document) ||
        /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
}

// Video functions
function playVideoOnIOS() {
    const video = document.getElementById('popupVideo');
    const overlay = document.getElementById('iosPlayOverlay');

    video.play().then(() => {
        console.log('Video played successfully on iOS');
        overlay.classList.remove('show');
        overlay.classList.add('hidden');
        video.style.display = 'block';
    }).catch(error => {
        console.log('Video play failed on iOS:', error);
        video.style.display = 'none';
        const fallback = document.querySelector('.fallback-image');
        if (fallback) {
            fallback.style.display = 'block';
        }
        overlay.classList.remove('show');
        overlay.classList.add('hidden');
    });
}

// Calendar functions
async function smartAddToCalendar() {
    const button = document.querySelector('.btn-save-date');
    const originalHTML = button.innerHTML;

    try {
        button.classList.add('loading');
        button.innerHTML = '<i class="fas fa-spinner"></i> Membuka...';

        const userAgent = navigator.userAgent.toLowerCase();
        const isAndroid = /android/.test(userAgent);

        await new Promise(resolve => setTimeout(resolve, 500));

        if (isAndroid) {
            addToGoogleCalendar();
        } else {
            showCalendarOptions();
        }

    } catch (error) {
        console.error('Error:', error);
        showToast('Terjadi kesalahan, silakan coba lagi', true);
    } finally {
        button.classList.remove('loading');
        button.innerHTML = originalHTML;
    }
}

function addToGoogleCalendar() {
    const eventDate = new Date(document.querySelector('[data-event-date]').dataset.eventDate);
    const endDate = new Date(eventDate);
    endDate.setHours(21, 0, 0, 0);

    const start = eventDate.toISOString().replace(/-|:|\.\d+/g, '');
    const end = endDate.toISOString().replace(/-|:|\.\d+/g, '');

    const details = {
        title: 'Pernikahan Vendy & Margareth',
        location: document.querySelector('[data-location]').dataset.location,
        description: 'Pernikahan Vendy & Margareth - ' + window.location.href
    };

    const url = [
        'https://calendar.google.com/calendar/render',
        '?action=TEMPLATE',
        '&text=' + encodeURIComponent(details.title),
        '&dates=' + start + '/' + end,
        '&details=' + encodeURIComponent(details.description),
        '&location=' + encodeURIComponent(details.location),
        '&sprop=website:' + encodeURIComponent(window.location.href),
        '&sprop=name:Undangan Pernikahan'
    ].join('');

    window.open(url, '_blank');
    showToast('Membuka Google Calendar...');
}

function downloadICalFile() {
    const eventDate = new Date(document.querySelector('[data-event-date]').dataset.eventDate);
    const endDate = new Date(eventDate);
    endDate.setHours(21, 0, 0, 0);

    const formatDate = (date) => {
        return date.toISOString().replace(/-|:|\.\d+/g, '').slice(0, 15) + 'Z';
    };

    const icalContent = [
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//Wedding Invitation//VendyMargareth//ID',
        'CALSCALE:GREGORIAN',
        'BEGIN:VEVENT',
        'UID:' + Date.now() + '@vendymargareth.wedding',
        'SUMMARY:Pernikahan Vendy & Margareth',
        'DESCRIPTION:Pernikahan Vendy Wiranatha dan Margaretha Magdalena Br Nainggolan - ' + window.location.href,
        'LOCATION:' + document.querySelector('[data-location]').dataset.location,
        'DTSTART:' + formatDate(eventDate),
        'DTEND:' + formatDate(endDate),
        'URL:' + window.location.href,
        'END:VEVENT',
        'END:VCALENDAR'
    ].join('\r\n');

    const blob = new Blob([icalContent], {type: 'text/calendar;charset=utf-8'});
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'Pernikahan-Vendy-Margareth.ics';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showToast('File kalender berhasil diunduh! Buka file untuk menambahkan ke kalender.');
}

function showCalendarOptions() {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    `;

    modal.innerHTML = `
        <div style="background: white; padding: 30px; border-radius: 20px; text-align: center; max-width: 320px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <h4 style="margin-bottom: 20px; color: #333; font-weight: 600;">Tambahkan ke Kalender</h4>
            <button onclick="addToGoogleCalendar(); closeModal(this)" style="background: linear-gradient(135deg, #4285F4 0%, #34A853 100%); color: white; border: none; padding: 15px 20px; border-radius: 25px; width: 100%; margin-bottom: 12px; cursor: pointer; font-weight: 500; transition: all 0.3s ease;">
                <i class="fab fa-google"></i> Google Calendar
            </button>
            <button onclick="downloadICalFile(); closeModal(this)" style="background: linear-gradient(135deg, #e44d26 0%, #f26161 100%); color: white; border: none; padding: 15px 20px; border-radius: 25px; width: 100%; margin-bottom: 12px; cursor: pointer; font-weight: 500; transition: all 0.3s ease;">
                <i class="fas fa-download"></i> Download iCal File
            </button>
            <button onclick="closeModal(this)" style="background: #f8f9fa; color: #666; border: 1px solid #dee2e6; padding: 12px 20px; border-radius: 25px; width: 100%; cursor: pointer; font-weight: 500; transition: all 0.3s ease;">
                Batal
            </button>
        </div>
    `;

    document.body.appendChild(modal);

    const modalButtons = modal.querySelectorAll('button');
    modalButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
        });
        btn.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    });
}

function closeModal(btn) {
    const modal = btn.closest('div[style*="position: fixed"]');
    if (modal) {
        document.body.removeChild(modal);
    }
}

function showToast(message, isError = false) {
    const existingToast = document.querySelector('.calendar-toast');
    if (existingToast) {
        document.body.removeChild(existingToast);
    }

    const toast = document.createElement('div');
    toast.className = 'calendar-toast';
    toast.style.cssText = `
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: ${isError ? '#dc3545' : '#333'};
        color: white;
        padding: 12px 25px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        z-index: 10000;
        opacity: 0;
        transition: all 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.transform = 'translateX(-50%) translateY(0)';
        toast.style.opacity = '1';
    }, 100);

    setTimeout(() => {
        toast.style.transform = 'translateX(-50%) translateY(100px)';
        toast.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('popupVideo');
    const overlay = document.getElementById('iosPlayOverlay');
    const fallbackImage = document.querySelector('.fallback-image');

    if (isIOS()) {
        if (overlay) {
            setTimeout(() => {
                overlay.style.display = 'flex';
                overlay.classList.add('show');
                overlay.classList.remove('hidden');
            }, 500);
        }

        if (video) {
            video.autoplay = false;
            video.load();
        }
    } else {
        if (video) {
            video.play().then(() => {
                if (overlay) {
                    overlay.style.display = 'none';
                }
            }).catch(error => {
                if (overlay) {
                    overlay.style.display = 'none';
                }
                if (fallbackImage) {
                    fallbackImage.style.display = 'block';
                }
            });
        }
    }

    if (video) {
        video.addEventListener('error', function () {
            this.style.display = 'none';
            if (fallbackImage) {
                fallbackImage.style.display = 'block';
            }
            if (overlay) {
                overlay.style.display = 'none';
            }
        });

        video.addEventListener('loadeddata', function () {
            if (!isIOS()) {
                this.play().catch(e => console.log('Play after load failed:', e));
            }
        });
    }

    // Auto flip
    const flipElements = [
        {id: 'flipper-groom', interval: null},
        {id: 'flipper-bride', interval: null}
    ];

    flipElements.forEach((element, index) => {
        setTimeout(() => {
            element.interval = setInterval(() => {
                const flipper = document.getElementById(element.id);
                if (flipper) {
                    flipper.classList.toggle('flipped');
                }
            }, 5000);
        }, index * 500);
    });

    window.addEventListener('beforeunload', function () {
        flipElements.forEach(element => {
            if (element.interval) {
                clearInterval(element.interval);
            }
        });
    });

    // Focus effects
    const formControls = document.querySelectorAll('.custom-input, .custom-select, .custom-textarea');

    formControls.forEach(control => {
        control.addEventListener('focus', function () {
            this.style.zIndex = '1000';
        });

        control.addEventListener('blur', function () {
            this.style.zIndex = '1';
        });
    });

    // Center the box
    function centerBox() {
        const container = document.querySelector('.konfirmasi-container');
        const box = document.querySelector('.konfirmasi-box');

        if (container && box) {
            const containerHeight = container.clientHeight;
            const boxHeight = box.clientHeight;
            const offset = (containerHeight - boxHeight) / 2;

            box.style.marginTop = offset > 0 ? '0' : '20px';
        }
    }

    window.addEventListener('resize', centerBox);
    centerBox();

    window.addEventListener('load', function () {
        setTimeout(function () {
            if (!audioStarted) {
                startAudio();
            }
        }, 1000);
    });
});

// Form submission
$(document).ready(function () {
    $('#konfirmasi-form').on('submit', function (e) {
        e.preventDefault();

        const submitBtn = $('#send-konfirmasi');
        const btnText = submitBtn.find('.btn-text');
        const btnLoading = submitBtn.find('.btn-loading');

        btnText.addClass('d-none');
        btnLoading.removeClass('d-none');
        submitBtn.prop('disabled', true);

        const formData = new FormData(this);

        $.ajax({
            url: '/store-message',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    $('#successModal').modal('show');
                    $('#konfirmasi-form')[0].reset();
                    $('#char-count').text('0');
                    updateMessages(response.messages);
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr);
                alert('Terjadi kesalahan saat mengirim konfirmasi. Silakan coba lagi.');
            },
            complete: function () {
                btnText.removeClass('d-none');
                btnLoading.addClass('d-none');
                submitBtn.prop('disabled', false);
            }
        });
    });

    function updateMessages(messages) {
        const messagesContainer = $('#listkomentar');
        const totalUcapan = $('#totalDoa-view');
        const showAllBtn = $('#showallcomment-btn');
        const scrollIndicator = $('#scrollIndicator');

        if (messages.length > 0) {
            let messagesHTML = '';

            messages.forEach((message) => {
                const messageDate = new Date(message.created_at);
                const formattedDate = messageDate.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                messagesHTML += `
                <div class="comment-item">
                    <strong>${message.name}</strong>
                    <small>${formattedDate}</small>
                    <p>${message.message || ''}</p>
                </div>
            `;
            });

            messagesContainer.html(messagesHTML);
            totalUcapan.html(`<strong>${messages.length} Ucapan</strong>`);

            if (messages.length > 5) {
                messagesContainer
                    .removeClass('no-scroll')
                    .addClass('scroll-enabled')
                    .css({
                        'max-height': '400px',
                        'overflow-y': 'auto'
                    });

                if (scrollIndicator.length === 0) {
                    messagesContainer.after(`
                    <div class="scroll-indicator" id="scrollIndicator">
                        <i class="fas fa-chevron-down"></i>
                        <span>Scroll untuk melihat lebih banyak ucapan</span>
                    </div>
                `);
                } else {
                    scrollIndicator.show();
                }

                showAllBtn.removeClass('invisible');
                showAllBtn.text(`TAMPILKAN SEMUA (${messages.length})`);

                setupScrollEvents();

            } else {
                messagesContainer
                    .removeClass('scroll-enabled')
                    .addClass('no-scroll')
                    .css({
                        'max-height': 'none',
                        'overflow-y': 'visible'
                    });

                scrollIndicator.hide();
                showAllBtn.addClass('invisible');
            }

        } else {
            messagesContainer.html(`
            <div class="text-center no-comments">
                <p>Belum ada ucapan. Jadilah yang pertama mengucapkan selamat!</p>
            </div>
        `);

            messagesContainer
                .removeClass('scroll-enabled')
                .addClass('no-scroll')
                .css({
                    'max-height': 'none',
                    'overflow-y': 'visible'
                });

            scrollIndicator.hide();
            showAllBtn.addClass('invisible');
        }

        AOS.refresh();

        if (messages.length > 0) {
            setTimeout(() => {
                if (messages.length > 5) {
                    messagesContainer.scrollTop(0);
                }
            }, 300);
        }
    }

    function setupScrollEvents() {
        const messagesContainer = $('#listkomentar');
        const scrollIndicator = $('#scrollIndicator');

        messagesContainer.off('scroll');

        messagesContainer.on('scroll', function () {
            const scrollTop = $(this).scrollTop();

            if (scrollTop > 50) {
                scrollIndicator.fadeOut();
            }

            if (scrollTop === 0) {
                scrollIndicator.fadeIn();
            }
        });

        messagesContainer.hover(
            function () {
                if ($(this).scrollTop() === 0) {
                    scrollIndicator.fadeIn();
                }
            },
            function () {
                scrollIndicator.fadeOut();
            }
        );
    }

    $(document).on('click', '#showallcomment-btn', function () {
        const messagesContainer = $('#listkomentar');
        const scrollIndicator = $('#scrollIndicator');

        messagesContainer
            .removeClass('scroll-enabled')
            .addClass('no-scroll')
            .css({
                'max-height': 'none',
                'overflow-y': 'visible'
            });

        $(this).addClass('invisible');
        scrollIndicator.fadeOut();
    });

    $(document).ready(function () {
        const messagesContainer = $('#listkomentar');

        if (messagesContainer.hasClass('scroll-enabled')) {
            setupScrollEvents();
        }
    });

    $('#pesan-fm').on('input', function () {
        const count = $(this).val().length;
        $('#char-count').text(count);

        if (count > 240) {
            $('#char-count').css('color', '#e44d26');
        } else {
            $('#char-count').css('color', '#f26161');
        }
    });
});

document.addEventListener('touchstart', function () {
    if (isIOS()) {
        const overlay = document.getElementById('iosPlayOverlay');
        const video = document.getElementById('popupVideo');

        if (overlay && overlay.classList.contains('show') && video) {
            playVideoOnIOS();
        }
    }
});

$(document).on('shown.bs.modal', '#successModal', function () {
    setTimeout(function () {
        $('#successModal').modal('hide');
    }, 1000);
});

$(document).on('hidden.bs.modal', '#successModal', function () {
    $('#nama-fm').focus();
});
