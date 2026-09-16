{{-- Content-only partial for embedding in the dashboard Settings tab.
     All handlers (window.settingsTab / saveSettingsForm / uploadAsset /
     deleteAsset / uploadGallery / editGalleryCaption / deleteGallery /
     saveEventForm) are defined in dashboard.blade.php. --}}

@php
    $settings = $template->template_settings ?? [];
    $assets = $template->assets_config ?? [];
    $gallery = $template->getGalleryImages();

    // Every image the vintage-romance invitation renders. `default` is the
    // bundled file used when nothing has been uploaded yet.
    $assetGroups = [
        'Foto & Ornamen' => [
            'bride_photo' => ['label' => 'Foto Mempelai Wanita', 'default' => 'assets/vintage/vendor/cewek.jpg'],
            'groom_photo' => ['label' => 'Foto Mempelai Pria', 'default' => 'assets/vintage/vendor/cowok.jpg'],
            'cover_photo' => ['label' => 'Foto Cover Depan (Sampul "BUKA UNDANGAN")', 'default' => 'assets/vintage/vendor/CB-VIN-2-FIX-RE.jpg'],
            'hero_photo' => ['label' => 'Foto "A Journey of Love Begins" (Hero)', 'default' => 'assets/vintage/vendor/cewek.jpg'],
            'story_image' => ['label' => 'Foto Our Story', 'default' => 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_40_16-AM.jpg'],
            'closing_image' => ['label' => 'Foto Penutup / Terima Kasih', 'default' => 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_42_15-AM.jpg'],
            'logo_image' => ['label' => 'Logo / Monogram', 'default' => 'assets/vintage/vendor/LOGO-VIN-2.png'],
            'flower_decoration' => ['label' => 'Dekorasi Bunga', 'default' => 'assets/vintage/vendor/BUNGA-VIN-2.png'],
            'lamp_decoration' => ['label' => 'Dekorasi Lampu', 'default' => 'assets/vintage/vendor/LAMPU-VIN-2.png'],
            'curtain_decoration' => ['label' => 'Dekorasi Tirai / Hordeng', 'default' => 'assets/vintage/vendor/HORDENG-VIN-2.png'],
            'divider_image' => ['label' => 'Garis Pemisah / Divider', 'default' => 'assets/vintage/vendor/DIVIDER-VIN-2.png'],
            'floral_border' => ['label' => 'Border Bunga (Atas)', 'default' => 'assets/vintage/vendor/AhaConvert_BUNGA-VIN-2B.webp'],
            'scroll_gif' => ['label' => 'Animasi Scroll', 'default' => 'assets/vintage/vendor/Animation-174404519592-scroll.gif'],
            'dresscode_image' => ['label' => 'Gambar Dress Code', 'default' => 'assets/vintage/vendor/dresscode-color.png'],
            'bank_bni_logo' => ['label' => 'Logo Bank BNI', 'default' => 'assets/vintage/vendor/bni.png'],
            'bank_bri_logo' => ['label' => 'Logo Bank BRI', 'default' => 'assets/vintage/vendor/Bank-Rakyat-Indonesia-BRI.png'],
        ],
        'Background Section' => [
            'bg_slide_1' => ['label' => 'Background Slide 1', 'default' => 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_35_26-AM.jpg'],
            'bg_slide_2' => ['label' => 'Background Slide 2', 'default' => 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_36_39-AM.jpg'],
            'bg_cover' => ['label' => 'Background Cover', 'default' => 'assets/vintage/vendor/BG-COVER-VIN-2-FIX.jpg'],
            'bg_paper' => ['label' => 'Kertas Bunga', 'default' => 'assets/vintage/vendor/PAPER-BG-FLORAL-Q.jpg'],
            'bg_section' => ['label' => 'Background Section', 'default' => 'assets/vintage/vendor/BG-VIIN-2.jpg'],
            'gift_card_bg' => ['label' => 'Kartu Hadiah', 'default' => 'assets/vintage/vendor/ATC-CARD-1.jpg'],
            'modal_overlay' => ['label' => 'Cover Undangan', 'default' => 'assets/vintage/vendor/CB-VIN-2-FIX-RE.jpg'],
            'desktop_cover' => ['label' => 'Cover Depan Desktop (layar "THE WEDDING OF")', 'default' => 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_40_16-AM.jpg'],
            'bg_all' => ['label' => 'Background Utama', 'default' => 'assets/vintage/vendor/BG-ALL-VIN-2.jpg'],
            'bg_plain_paper' => ['label' => 'Kertas Polos', 'default' => 'assets/vintage/vendor/paper-plos-p-1.jpg'],
        ],
    ];
    $imageAssets = array_merge(...array_values($assetGroups));

    $toggles = [
        'show_bride_photo' => 'Foto Mempelai Wanita',
        'show_groom_photo' => 'Foto Mempelai Pria',
        'show_story_image' => 'Foto Our Story',
        'show_gallery' => 'Section Gallery',
        'show_countdown' => 'Section Countdown',
        'show_gift' => 'Section Wedding Gift',
    ];

    $eventLabels = ['gedung' => 'Resepsi (Gedung)', 'rumah' => 'Akad / Rumah'];
@endphp

<style>
    .ts-wrap .settings-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,.05); margin-bottom: 20px; overflow: hidden; }
    .ts-wrap .settings-card-header { background: linear-gradient(135deg, #e44d26, #f26161); color: #fff; padding: 14px 18px; font-weight: 600; }
    .ts-wrap .settings-card-body { padding: 18px; }
    .ts-wrap .form-label { font-weight: 600; color: #2d3748; margin-bottom: 6px; font-size: .88rem; }
    .ts-wrap .btn-primary-custom { background: #e44d26; border: none; padding: 9px 18px; border-radius: 8px; color: #fff; }
    .ts-wrap .btn-primary-custom:hover { background: #f26161; color: #fff; }
    .ts-wrap .nav-tabs .nav-link { color: #2d3748; border: 1px solid #dee2e6; border-radius: 8px 8px 0 0; font-size: .85rem; padding: 8px 14px; }
    .ts-wrap .nav-tabs .nav-link.active { background: #e44d26; color: #fff; border-color: #e44d26; }
    .ts-wrap .nav-tabs .nav-link:hover:not(.active) { border-color: #e44d26; color: #e44d26; }
    .ts-wrap .asset-thumb { height: 130px; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 8px; background: #f8f9fa; border: 1px solid #eef0f2; }
    .ts-wrap .asset-thumb img { max-height: 128px; max-width: 100%; object-fit: contain; }
    .ts-wrap .asset-key { font-size: .7rem; color: #9ca3af; word-break: break-all; }
    .ts-wrap .badge-uploaded { font-size: .65rem; background: #d1fae5; color: #065f46; border-radius: 10px; padding: 2px 8px; }
    .ts-wrap .badge-default { font-size: .65rem; background: #f3f4f6; color: #6b7280; border-radius: 10px; padding: 2px 8px; }
    .ts-wrap .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; }
    .ts-wrap .gallery-item { position: relative; border-radius: 10px; overflow: hidden; aspect-ratio: 1; background: #f0f0f0; }
    .ts-wrap .gallery-item img { width: 100%; height: 100%; object-fit: cover; }
    .ts-wrap .gallery-item-overlay { position: absolute; inset: auto 0 0 0; background: linear-gradient(transparent, rgba(0,0,0,.75)); padding: 8px; }
    .ts-wrap .gallery-item-caption { color: #fff; font-size: .75rem; margin-bottom: 4px; word-break: break-word; }
    /* Drag-and-drop reorder */
    .ts-wrap .gallery-item .drag-handle { position: absolute; top: 6px; left: 6px; width: 26px; height: 26px; border-radius: 6px; background: rgba(0,0,0,.55); color: #fff; display: flex; align-items: center; justify-content: center; font-size: .72rem; cursor: grab; z-index: 5; touch-action: none; }
    .ts-wrap .gallery-item .drag-handle:active { cursor: grabbing; }
    .ts-wrap .gallery-item .order-badge { position: absolute; top: 6px; right: 6px; min-width: 22px; height: 22px; padding: 0 6px; border-radius: 11px; background: #e44d26; color: #fff; font-size: .68rem; font-weight: 700; display: flex; align-items: center; justify-content: center; z-index: 5; }
    .ts-wrap .gallery-item.dragging { opacity: .45; transform: scale(.97); }
    .ts-wrap .gallery-item.drag-over { outline: 2px dashed #e44d26; outline-offset: -2px; }
    .ts-wrap .reorder-hint { font-size: .78rem; color: #6b7280; }
    .ts-wrap .form-check-label { font-size: .85rem; }
    .ts-wrap .ceremony-row { border: 1px dashed #e2e8f0; border-radius: 10px; padding: 12px; margin-bottom: 10px; background: #fbfcfe; }
    .ts-wrap .ceremony-row .form-label { font-size: .8rem; }
</style>

<div class="ts-wrap p-2">
    <h4 class="mb-3"><i class="fas fa-cog me-2"></i>Pengaturan Template: {{ $template->name }}</h4>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item"><button type="button" class="nav-link active" data-bs-target="#ts-couple" onclick="window.settingsTab(this)"><i class="fas fa-heart me-1"></i> Mempelai</button></li>
        <li class="nav-item"><button type="button" class="nav-link" data-bs-target="#ts-photos" onclick="window.settingsTab(this)"><i class="fas fa-image me-1"></i> Foto ({{ count($imageAssets) }})</button></li>
        <li class="nav-item"><button type="button" class="nav-link" data-bs-target="#ts-gallery" onclick="window.settingsTab(this)"><i class="fas fa-images me-1"></i> Gallery (<span id="tsGalleryTabCount">{{ count($gallery) }}</span>)</button></li>
        <li class="nav-item"><button type="button" class="nav-link" data-bs-target="#ts-events" onclick="window.settingsTab(this)"><i class="fas fa-calendar-alt me-1"></i> Acara</button></li>
        <li class="nav-item"><button type="button" class="nav-link" data-bs-target="#ts-texts" onclick="window.settingsTab(this)"><i class="fas fa-font me-1"></i> Teks</button></li>
        <li class="nav-item"><button type="button" class="nav-link" data-bs-target="#ts-gift" onclick="window.settingsTab(this)"><i class="fas fa-gift me-1"></i> Hadiah</button></li>
    </ul>

    <div class="tab-content">

        {{-- ================= MEMPELAI ================= --}}
        <div class="tab-pane fade show active" id="ts-couple">
            <form id="tsCoupleForm" onsubmit="event.preventDefault(); window.saveSettingsForm('tsCoupleForm');">
                @csrf
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-venus me-2"></i>Mempelai Wanita</div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Panggilan</label>
                                <input type="text" class="form-control" name="template_settings[bride_name]" value="{{ $settings['bride_name'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="template_settings[bride_full_name]" value="{{ $settings['bride_full_name'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Ayah</label>
                                <input type="text" class="form-control" name="template_settings[bride_father]" value="{{ $settings['bride_father'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Ibu</label>
                                <input type="text" class="form-control" name="template_settings[bride_mother]" value="{{ $settings['bride_mother'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Instagram</label>
                                <input type="text" class="form-control" name="template_settings[bride_instagram]" value="{{ $settings['bride_instagram'] ?? '' }}" placeholder="https://instagram.com/username">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Foto Mempelai Wanita</label>
                                <div class="asset-thumb mb-2">
                                    <img src="{{ $template->getAssetUrl('bride_photo', 'assets/vintage/vendor/cewek.jpg') }}" alt="Bride">
                                </div>
                                <div class="d-flex gap-1">
                                    <label class="btn btn-outline-primary btn-sm mb-0"><i class="fas fa-upload me-1"></i> Upload<input type="file" class="d-none" accept="image/jpeg,image/png,image/webp,image/gif" onchange="window.uploadAsset(this, 'bride_photo')"></label>
                                    @if(!empty($assets['bride_photo']))
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.deleteAsset('bride_photo')"><i class="fas fa-trash"></i></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-mars me-2"></i>Mempelai Pria</div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Panggilan</label>
                                <input type="text" class="form-control" name="template_settings[groom_name]" value="{{ $settings['groom_name'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="template_settings[groom_full_name]" value="{{ $settings['groom_full_name'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Ayah</label>
                                <input type="text" class="form-control" name="template_settings[groom_father]" value="{{ $settings['groom_father'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Ibu</label>
                                <input type="text" class="form-control" name="template_settings[groom_mother]" value="{{ $settings['groom_mother'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Instagram</label>
                                <input type="text" class="form-control" name="template_settings[groom_instagram]" value="{{ $settings['groom_instagram'] ?? '' }}" placeholder="https://instagram.com/username">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Foto Mempelai Pria</label>
                                <div class="asset-thumb mb-2">
                                    <img src="{{ $template->getAssetUrl('groom_photo', 'assets/vintage/vendor/cowok.jpg') }}" alt="Groom">
                                </div>
                                <div class="d-flex gap-1">
                                    <label class="btn btn-outline-primary btn-sm mb-0"><i class="fas fa-upload me-1"></i> Upload<input type="file" class="d-none" accept="image/jpeg,image/png,image/webp,image/gif" onchange="window.uploadAsset(this, 'groom_photo')"></label>
                                    @if(!empty($assets['groom_photo']))
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.deleteAsset('groom_photo')"><i class="fas fa-trash"></i></button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subtitle (Bride &amp; Groom)</label>
                                <input type="text" class="form-control" name="template_settings[couple_subtitle]" value="{{ $settings['couple_subtitle'] ?? '' }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary-custom mt-3"><i class="fas fa-save me-1"></i> Simpan Data Mempelai</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================= FOTO & ASSET ================= --}}
        <div class="tab-pane fade" id="ts-photos">
            <div class="settings-card">
                <div class="settings-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-image me-2"></i>Semua Foto Template</span>
                    <span class="badge bg-light text-dark">{{ count(array_intersect_key($assets, $imageAssets)) }} dari {{ count($imageAssets) }} diupload</span>
                </div>
                <div class="settings-card-body">
                    <p class="text-muted" style="font-size:.85rem;">
                        Upload pengganti untuk foto bawaan template. Format <strong>JPG, PNG, WebP, GIF</strong> (maks 8MB).
                        Foto besar akan <strong>dikompres otomatis di browser</strong> sebelum dikirim, jadi foto dari HP
                        (4-12MB) tetap bisa diupload tanpa perlu diperkecil manual. Animasi GIF dibiarkan utuh.
                    </p>

                    @foreach($assetGroups as $groupTitle => $groupAssets)
                        <h6 class="mt-3 mb-2 text-muted" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">{{ $groupTitle }}</h6>
                        <div class="row g-3">
                            @foreach($groupAssets as $key => $info)
                                <div class="col-md-4 col-6">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            @php
                                                // The hero photo falls back to the uploaded bride photo
                                                // (there is no separate bundled artwork for it).
                                                $previewDefault = $key === 'hero_photo'
                                                    ? ($assets['bride_photo'] ?? $info['default'])
                                                    : $info['default'];
                                            @endphp
                                            <div class="asset-thumb mb-2">
                                                <img src="{{ $template->getAssetUrl($key, $previewDefault) }}" alt="{{ $info['label'] }}">
                                            </div>
                                            <h6 class="mb-1" style="font-size:.85rem;">{{ $info['label'] }}</h6>
                                            <div class="asset-key mb-2">
                                                {{ $key }}
                                                @if(!empty($assets[$key]))
                                                    <span class="badge-uploaded">custom</span>
                                                @else
                                                    <span class="badge-default">default</span>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-1 justify-content-center">
                                                <label class="btn btn-outline-primary btn-sm mb-0" style="font-size:.75rem;"><i class="fas fa-upload"></i><input type="file" class="d-none" accept="image/jpeg,image/png,image/webp,image/gif" onchange="window.uploadAsset(this, '{{ $key }}')"></label>
                                                @if(!empty($assets[$key]))
                                                    <button type="button" class="btn btn-outline-danger btn-sm" style="font-size:.75rem;" onclick="window.deleteAsset('{{ $key }}')"><i class="fas fa-trash"></i></button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ================= GALLERY ================= --}}
        <div class="tab-pane fade" id="ts-gallery">
            <div class="settings-card">
                <div class="settings-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-images me-2"></i>Gallery Foto</span>
                    <span class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark" id="tsGalleryCount">{{ count($gallery) }} foto</span>
                        <label class="btn btn-sm btn-light mb-0">
                            <i class="fas fa-upload me-1"></i> Upload Foto
                            <input type="file" id="tsGalleryUpload" multiple accept="image/jpeg,image/png,image/webp,image/gif" style="display:none;" onchange="window.uploadGallery(this)">
                        </label>
                    </span>
                </div>
                <div class="settings-card-body">
                    @if(count($gallery) > 0)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="reorder-hint"><i class="fas fa-arrows-alt me-1"></i> Seret foto (ikon <i class="fas fa-grip-vertical"></i>) untuk mengubah urutan tampil di undangan</div>
                            <span class="reorder-hint" id="tsGallerySaved" style="opacity:0;color:#10b981;transition:opacity .3s;"><i class="fas fa-check-circle"></i> Tersimpan</span>
                        </div>
                        <div class="gallery-grid" id="tsGalleryGrid">
                            @foreach($gallery as $idx => $img)
                                <div class="gallery-item" data-path="{{ $img['path'] }}">
                                    <div class="drag-handle" title="Seret untuk mengubah urutan"><i class="fas fa-grip-vertical"></i></div>
                                    <div class="order-badge">{{ $idx + 1 }}</div>
                                    <img src="{{ $template->getGalleryImageUrl($img) }}" alt="{{ $img['caption'] ?? 'Gallery' }}" draggable="false">
                                    <div class="gallery-item-overlay">
                                        <div class="gallery-item-caption">{{ $img['caption'] ?? '' }}</div>
                                        <div class="d-flex gap-1 justify-content-end">
                                            <button type="button" class="btn btn-light btn-sm" onclick="window.editGalleryCaption(@js($img['caption'] ?? ''), @js($img['path']))"><i class="fas fa-edit"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="window.deleteGallery(@js($img['path']))"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-images fa-3x mb-2"></i>
                            <p class="mb-0">Belum ada foto. Klik <strong>Upload Foto</strong> untuk menambah.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ================= ACARA ================= --}}
        <div class="tab-pane fade" id="ts-events">
            <div class="settings-card">
                <div class="settings-card-header"><i class="fas fa-calendar-alt me-2"></i>Judul Section Acara</div>
                <div class="settings-card-body">
                    <form id="tsEventLabelsForm" onsubmit="event.preventDefault(); window.saveSettingsForm('tsEventLabelsForm');">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Judul Besar</label>
                                <input type="text" class="form-control" name="template_settings[wedding_day_title]" value="{{ $settings['wedding_day_title'] ?? 'Wedding Day' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kalimat Pengantar</label>
                                <input type="text" class="form-control" name="template_settings[wedding_day_subtitle]" value="{{ $settings['wedding_day_subtitle'] ?? 'InsyaAllah akan dilaksanakan pada:' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Label Acara Pertama</label>
                                <input type="text" class="form-control" name="template_settings[event_akad_title]" value="{{ $settings['event_akad_title'] ?? 'Akad Nikah' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Label Acara Kedua</label>
                                <input type="text" class="form-control" name="template_settings[event_resepsi_title]" value="{{ $settings['event_resepsi_title'] ?? 'Resepsi Pernikahan' }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary-custom mt-3"><i class="fas fa-save me-1"></i> Simpan Judul</button>
                    </form>
                </div>
            </div>

            <p class="text-muted" style="font-size:.85rem;">
                Tambahkan sebanyak apa pun acara pada satu undangan. Acara <strong>Utama</strong> dipakai untuk
                countdown &amp; tanggal undangan, sedangkan acara <strong>tambahan</strong> ditampilkan berurutan
                di bawahnya pada section "Wedding Day".
            </p>

            @foreach(['gedung', 'rumah'] as $eventKey)
                @php
                    $event = $events[$eventKey] ?? null;
                    $primaryDate = $event && $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') : '';
                @endphp
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i class="fas fa-map-marker-alt me-2"></i>{{ $eventLabels[$eventKey] }}
                        <small class="ms-2" style="opacity:.8;">({{ $eventKey === 'gedung' ? '/p/invitation' : '/r/invitation' }})</small>
                    </div>
                    <div class="settings-card-body">
                        <form id="tsEventForm{{ ucfirst($eventKey) }}" onsubmit="event.preventDefault(); window.saveEventForm('tsEventForm{{ ucfirst($eventKey) }}');">
                            @csrf
                            <input type="hidden" name="event_key" value="{{ $eventKey }}">

                            <h6 class="fw-bold mb-2" style="font-size:.85rem;">Acara Utama</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nama Acara</label>
                                    <input type="text" class="form-control" name="title" value="{{ $event->title ?? '' }}" placeholder="{{ $eventKey === 'gedung' ? 'Resepsi Pernikahan' : 'Akad Nikah' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" name="event_date" value="{{ $primaryDate }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Waktu Mulai</label>
                                    <input type="text" class="form-control" name="start_time" value="{{ $event->start_time ?? '' }}" placeholder="08:00" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Waktu Selesai</label>
                                    <input type="text" class="form-control" name="finish_time" value="{{ $event->finish_time ?? '' }}" placeholder="Selesai" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Link Google Maps</label>
                                    <input type="text" class="form-control" name="google_map_link" value="{{ $event->google_map_link ?? '' }}" placeholder="https://maps.app.goo.gl/...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nama Tempat / Lokasi</label>
                                    <input type="text" class="form-control" name="location" value="{{ $event->location ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control" name="address" rows="1" placeholder="Jalan, RT/RW, Kecamatan, Kabupaten">{{ $event->address ?? '' }}</textarea>
                                </div>
                            </div>

                            <h6 class="fw-bold mt-4 mb-2" style="font-size:.85rem;">Acara Tambahan</h6>
                            <div id="tsCeremonyRows{{ ucfirst($eventKey) }}">
                                @foreach(($event->details ?? collect()) as $detail)
                                    <div class="ceremony-row" data-ceremony-row>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="form-label mb-0">Acara Tambahan #<span data-ceremony-number>{{ $loop->iteration }}</span></label>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.removeCeremonyRow(this)"><i class="fas fa-times"></i> Hapus</button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nama Acara</label>
                                                <input type="text" class="form-control" data-field="title" value="{{ $detail->title }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Tanggal</label>
                                                <input type="date" class="form-control" data-field="event_date" value="{{ $detail->event_date ? $detail->event_date->format('Y-m-d') : '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Waktu Mulai</label>
                                                <input type="text" class="form-control" data-field="start_time" value="{{ $detail->start_time }}" placeholder="08:00">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Waktu Selesai</label>
                                                <input type="text" class="form-control" data-field="finish_time" value="{{ $detail->finish_time }}" placeholder="Selesai">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label">Link Google Maps</label>
                                                <input type="text" class="form-control" data-field="google_map_link" value="{{ $detail->google_map_link }}" placeholder="https://maps.app.goo.gl/...">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nama Tempat / Lokasi</label>
                                                <input type="text" class="form-control" data-field="location" value="{{ $detail->location }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Alamat Lengkap</label>
                                                <textarea class="form-control" data-field="address" rows="1" placeholder="Jalan, RT/RW, Kecamatan, Kabupaten">{{ $detail->address }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="window.addCeremonyRow('tsCeremonyRows{{ ucfirst($eventKey) }}')">
                                <i class="fas fa-plus me-1"></i> Tambah Acara
                            </button>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary-custom"><i class="fas fa-save me-1"></i> Simpan {{ $eventLabels[$eventKey] }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ================= TEKS ================= --}}
        <div class="tab-pane fade" id="ts-texts">
            <form id="tsTextsForm" onsubmit="event.preventDefault(); window.saveSettingsForm('tsTextsForm');">
                @csrf
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-heading me-2"></i>Cover / Hero</div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Judul Hero</label>
                                <input type="text" class="form-control" name="template_settings[hero_title]" value="{{ $settings['hero_title'] ?? 'THE WEDDING OF' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subtitle Hero</label>
                                <input type="text" class="form-control" name="template_settings[hero_subtitle]" value="{{ $settings['hero_subtitle'] ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Ayat / Kutipan</label>
                                <textarea class="form-control" name="template_settings[hero_verse]" rows="4">{{ $settings['hero_verse'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sumber Ayat</label>
                                <input type="text" class="form-control" name="template_settings[hero_verse_source]" value="{{ $settings['hero_verse_source'] ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Link Live Streaming (opsional)</label>
                                <input type="text" class="form-control" name="template_settings[live_stream_url]" value="{{ $settings['live_stream_url'] ?? '' }}" placeholder="https://youtube.com/live/...">
                                <small class="text-muted">Tombol "Join Live Streaming" di undangan akan mengarah ke link ini.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-book-open me-2"></i>Our Story &amp; Gallery</div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Judul Our Story</label>
                                <input type="text" class="form-control" name="template_settings[story_title]" value="{{ $settings['story_title'] ?? 'Our Story' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subtitle Our Story</label>
                                <input type="text" class="form-control" name="template_settings[story_subtitle]" value="{{ $settings['story_subtitle'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Judul Gallery</label>
                                <input type="text" class="form-control" name="template_settings[gallery_title]" value="{{ $settings['gallery_title'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subtitle Gallery</label>
                                <input type="text" class="form-control" name="template_settings[gallery_subtitle]" value="{{ $settings['gallery_subtitle'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-clock me-2"></i>Countdown, RSVP &amp; Hadiah</div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Judul Countdown</label>
                                <input type="text" class="form-control" name="template_settings[countdown_title]" value="{{ $settings['countdown_title'] ?? 'Counting The Days' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Judul RSVP</label>
                                <input type="text" class="form-control" name="template_settings[rsvp_title]" value="{{ $settings['rsvp_title'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subtitle RSVP</label>
                                <input type="text" class="form-control" name="template_settings[rsvp_subtitle]" value="{{ $settings['rsvp_subtitle'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Judul Hadiah</label>
                                <input type="text" class="form-control" name="template_settings[gift_title]" value="{{ $settings['gift_title'] ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Subtitle Hadiah</label>
                                <textarea class="form-control" name="template_settings[gift_subtitle]" rows="2">{{ $settings['gift_subtitle'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-heart me-2"></i>Terima Kasih &amp; Penutup</div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Judul Terima Kasih</label>
                                <input type="text" class="form-control" name="template_settings[thank_title]" value="{{ $settings['thank_title'] ?? 'Terima Kasih' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teks Wassalam</label>
                                <input type="text" class="form-control" name="template_settings[wassalam_text]" value="{{ $settings['wassalam_text'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teks Terima Kasih</label>
                                <textarea class="form-control" name="template_settings[thank_text]" rows="3">{{ $settings['thank_text'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kalimat Penutup</label>
                                <textarea class="form-control" name="template_settings[thank_closing]" rows="3">{{ $settings['thank_closing'] ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Pesan Footer</label>
                                <textarea class="form-control" name="template_settings[footer_message]" rows="2">{{ $settings['footer_message'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-eye me-2"></i>Tampilan Section</div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            @foreach($toggles as $key => $label)
                                <div class="col-md-4 col-6">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="template_settings[{{ $key }}]" value="0">
                                        <input class="form-check-input" type="checkbox" name="template_settings[{{ $key }}]" value="1" id="toggle-{{ $key }}" {{ (($settings[$key] ?? '1') === '0') ? '' : 'checked' }}>
                                        <label class="form-check-label" for="toggle-{{ $key }}">{{ $label }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted d-block mt-2">Matikan switch untuk menyembunyikan bagian tersebut dari undangan.</small>
                        <button type="submit" class="btn btn-primary-custom mt-3"><i class="fas fa-save me-1"></i> Simpan Semua Teks</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================= HADIAH / REKENING ================= --}}
        <div class="tab-pane fade" id="ts-gift">
            <form id="tsGiftForm" onsubmit="event.preventDefault(); window.saveSettingsForm('tsGiftForm');">
                @csrf
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-university me-2"></i>Rekening Bank BNI</div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" class="form-control" name="assets_config[bank_bni_number]" value="{{ $assets['bank_bni_number'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Atas Nama</label>
                                <input type="text" class="form-control" name="assets_config[bank_bni_name]" value="{{ $assets['bank_bni_name'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Logo Bank BNI</label>
                                <div class="asset-thumb" style="height:70px;">
                                    <img src="{{ $template->getAssetUrl('bank_bni_logo', 'assets/vintage/vendor/bni.png') }}" alt="BNI" style="max-height:68px;">
                                </div>
                                <div class="d-flex gap-1 mt-2">
                                    <label class="btn btn-outline-primary btn-sm mb-0"><i class="fas fa-upload me-1"></i> Upload<input type="file" class="d-none" accept="image/jpeg,image/png,image/webp,image/gif" onchange="window.uploadAsset(this, 'bank_bni_logo')"></label>
                                    @if(!empty($assets['bank_bni_logo']))
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.deleteAsset('bank_bni_logo')"><i class="fas fa-trash"></i></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-university me-2"></i>Rekening Bank BRI</div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" class="form-control" name="assets_config[bank_bri_number]" value="{{ $assets['bank_bri_number'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Atas Nama</label>
                                <input type="text" class="form-control" name="assets_config[bank_bri_name]" value="{{ $assets['bank_bri_name'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Logo Bank BRI</label>
                                <div class="asset-thumb" style="height:70px;">
                                    <img src="{{ $template->getAssetUrl('bank_bri_logo', 'assets/vintage/vendor/Bank-Rakyat-Indonesia-BRI.png') }}" alt="BRI" style="max-height:68px;">
                                </div>
                                <div class="d-flex gap-1 mt-2">
                                    <label class="btn btn-outline-primary btn-sm mb-0"><i class="fas fa-upload me-1"></i> Upload<input type="file" class="d-none" accept="image/jpeg,image/png,image/webp,image/gif" onchange="window.uploadAsset(this, 'bank_bri_logo')"></label>
                                    @if(!empty($assets['bank_bri_logo']))
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.deleteAsset('bank_bri_logo')"><i class="fas fa-trash"></i></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-box-open me-2"></i>Kirim Kado (Alamat)</div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control" name="assets_config[physical_gift_name]" value="{{ $assets['physical_gift_name'] ?? '' }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Alamat Pengiriman</label>
                                <textarea class="form-control" name="assets_config[physical_gift_address]" rows="2">{{ $assets['physical_gift_address'] ?? '' }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary-custom mt-3"><i class="fas fa-save me-1"></i> Simpan Data Hadiah</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

{{-- Blueprint for one "acara tambahan" row. Names are assigned by
     window.addCeremonyRow()/reindexCeremonyRows() so add/remove never
     produces duplicate or gapped details[] indices. --}}
<template id="tsCeremonyRowTemplate">
    <div class="ceremony-row" data-ceremony-row>
        <div class="row g-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label mb-0">Acara Tambahan #<span data-ceremony-number></span></label>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.removeCeremonyRow(this)"><i class="fas fa-times"></i> Hapus</button>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nama Acara</label>
                <input type="text" class="form-control" data-field="title" placeholder="Resepsi / Akad Nikah">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" data-field="event_date">
            </div>
            <div class="col-md-4">
                <label class="form-label">Waktu Mulai</label>
                <input type="text" class="form-control" data-field="start_time" placeholder="08:00">
            </div>
            <div class="col-md-4">
                <label class="form-label">Waktu Selesai</label>
                <input type="text" class="form-control" data-field="finish_time" placeholder="Selesai">
            </div>
            <div class="col-md-8">
                <label class="form-label">Link Google Maps</label>
                <input type="text" class="form-control" data-field="google_map_link" placeholder="https://maps.app.goo.gl/...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Tempat / Lokasi</label>
                <input type="text" class="form-control" data-field="location">
            </div>
            <div class="col-md-6">
                <label class="form-label">Alamat Lengkap</label>
                <textarea class="form-control" data-field="address" rows="1" placeholder="Jalan, RT/RW, Kecamatan, Kabupaten"></textarea>
            </div>
        </div>
    </div>
</template>
