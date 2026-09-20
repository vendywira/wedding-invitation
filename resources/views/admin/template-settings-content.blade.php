{{-- Content-only partial for embedding in the dashboard Settings tab.

     All handlers (window.settingsTab / saveSettingsForm / uploadAsset /

     deleteAsset / uploadGallery / editGalleryCaption / deleteGallery /

     saveGroupForm / saveNewGroup / deleteGroup) are defined in dashboard.blade.php. --}}



@php

    $settings = $template->template_settings ?? [];

    $assets = $template->assets_config ?? [];

    $gallery = $template->getGalleryImages();



    // Foto bawaan berbeda per template, jadi preview di panel ini mengikuti

    // template yang sedang aktif: selama fotonya belum diupload, yang tampil

    // memang foto bawaan template tersebut (bukan foto template lain).

    $photoDefaults = [

        'bride_photo' => 'assets/vintage/vendor/cewek.jpg',

        'groom_photo' => 'assets/vintage/vendor/cowok.jpg',

        'hero_photo' => 'assets/vintage/vendor/cewek.jpg',

        'story_image' => 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_40_16-AM.jpg',

        'closing_image' => 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_42_15-AM.jpg',

        'desktop_cover' => 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_40_16-AM.jpg',

        'cover_photo' => 'assets/vintage/vendor/CB-VIN-2-FIX-RE.jpg',

        'logo_image' => 'assets/vintage/vendor/LOGO-VIN-2.png',

        'bg_slide_1' => 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_35_26-AM.jpg',

        'bg_slide_2' => 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_36_39-AM.jpg',

    ];



    $photoDefaults = array_merge($photoDefaults, [

        'elegant-floral' => [

            'bride_photo' => 'assets/images/gallery/bride.jpg',

            'groom_photo' => 'assets/images/gallery/groom.jpg',

            'hero_photo' => 'assets/images/gallery/gal-2.jpg',

            'story_image' => 'assets/images/gallery/gal-3.jpg',

            'closing_image' => 'assets/images/gallery/gal-4.jpg',

            'desktop_cover' => 'assets/images/gallery/gal-1.jpg',

            'cover_photo' => 'assets/images/og-image.jpg',

            'logo_image' => 'assets/images/tittle-section.png',

            'bg_slide_1' => 'assets/images/gallery/slide1.jpg',

            'bg_slide_2' => 'assets/images/gallery/slide2.jpg',

        ],

    ][$template->slug] ?? []);



    $assetGroups = [

        'Foto' => [

            'bride_photo' => ['label' => 'Foto Mempelai Wanita', 'default' => $photoDefaults['bride_photo']],

            'groom_photo' => ['label' => 'Foto Mempelai Pria', 'default' => $photoDefaults['groom_photo']],

            'hero_photo' => ['label' => 'Foto Journey of Love', 'default' => $photoDefaults['hero_photo']],

            'story_image' => ['label' => 'Foto Our Story', 'default' => $photoDefaults['story_image']],

            'closing_image' => ['label' => 'Foto Penutup / Terima Kasih', 'default' => $photoDefaults['closing_image']],

            'desktop_cover' => ['label' => 'Cover Depan Desktop', 'default' => $photoDefaults['desktop_cover']],

            'cover_photo' => ['label' => 'Cover Sampul (Buka Undangan)', 'default' => $photoDefaults['cover_photo']],

            'logo_image' => ['label' => 'Logo / Monogram', 'default' => $photoDefaults['logo_image']],

        ],

        'Slide Background' => [

            'bg_slide_1' => ['label' => 'Slide 1', 'default' => $photoDefaults['bg_slide_1']],

            'bg_slide_2' => ['label' => 'Slide 2', 'default' => $photoDefaults['bg_slide_2']],

        ],

    ];

    $imageAssets = array_merge(...array_values($assetGroups));



    // Switch tampil/sembunyi per section. Key-nya bebas (tersimpan di

    // template_settings) dan dipetakan ke satu elemen di invitation oleh

    // blok CSS "Section visibility toggles" di template.

    $toggles = [

        'show_bride_photo' => 'Foto Mempelai Wanita',

        'show_groom_photo' => 'Foto Mempelai Pria',

        'show_story_image' => 'Foto Our Story (fotonya saja)',

        'show_story' => 'Section Our Story',

        'show_gallery' => 'Section Gallery',

        'show_countdown' => 'Section Countdown',

        'show_gift' => 'Section Wedding Gift',

        'show_live' => 'Section Live Moment',

        'show_dresscode' => 'Section Dresscode',

        'show_best_wishes' => 'Section Best Wishes (komentar)',

        'show_footer' => 'Section Footer (penutup)',

    ];



    // Blank blueprint reused by window.addGiftRow() for a new gift entry.

    $giftTemplate = [

        'id' => '',

        'type' => 'bank',

        'label' => '',

        'number' => '',

        'holder' => '',

        'address' => '',

        'default_logo' => null,

    ];

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

    /* OG image picker: grid foto, klik untuk pilih jadi OG image */
    .og-picker-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; }

    .og-picker-item { display: block; width: 100%; position: relative; border: 2px solid #e5e7eb; border-radius: 10px; overflow: hidden; background: #fff; padding: 0; cursor: pointer; text-align: left; transition: border-color .15s, box-shadow .15s; }

    .og-picker-item:hover { border-color: #f26161; }

    .og-picker-item.selected { border-color: #e44d26; box-shadow: 0 0 0 3px rgba(228,77,38,.2); }

    .og-picker-thumb { aspect-ratio: 1200 / 630; background: #f3f4f6; overflow: hidden; }

    .og-picker-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .og-picker-empty { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: .75rem; gap: 6px; }

    .og-picker-label { padding: 6px 8px; font-size: .76rem; font-weight: 600; color: #2d3748; }

    .og-picker-check { position: absolute; top: 6px; right: 6px; width: 22px; height: 22px; border-radius: 50%; background: #e44d26; color: #fff; display: none; align-items: center; justify-content: center; font-size: .68rem; z-index: 2; }

    .og-picker-item.selected .og-picker-check { display: flex; }

    .og-picker-badge { position: absolute; top: 6px; left: 6px; z-index: 2; }

    /* SEO: preview card menampilkan teks default yang dipakai kalau field
       kosong, supaya user tahu persis apa yang dilihat tamu. */
    .seo-preview-card { margin-top: 8px; padding: 10px 12px; border: 1px dashed #e44d26; border-radius: 8px; background: #fff7f4; }

    .seo-preview-badge { font-size: .65rem; font-weight: 700; color: #e44d26; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 4px; }

    .seo-preview-text { font-size: .8rem; color: #4b5563; line-height: 1.45; }

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

    /* Kartu melayang yang mengikuti kursor saat drag. */
    .ts-wrap .gallery-item.drag-ghost { box-shadow: 0 14px 30px rgba(0, 0, 0, .28); border: 2px solid #e44d26; cursor: grabbing; }

    body.is-sorting-gallery { cursor: grabbing; }

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

        <li class="nav-item"><button type="button" class="nav-link" data-bs-target="#ts-story" onclick="window.settingsTab(this)"><i class="fas fa-heart me-1"></i> Our Story</button></li>

        <li class="nav-item"><button type="button" class="nav-link" data-bs-target="#ts-music" onclick="window.settingsTab(this)"><i class="fas fa-music me-1"></i> Musik</button></li>

        <li class="nav-item"><button type="button" class="nav-link" data-bs-target="#ts-seo" onclick="window.settingsTab(this)"><i class="fas fa-search me-1"></i> SEO</button></li>

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

                                <label class="form-label">Gelar Anak</label>

                                <input type="text" class="form-control" name="template_settings[bride_child_label]" value="{{ $settings['bride_child_label'] ?? 'Putri dari' }}" placeholder="Putri dari">

                                <div class="form-text">Teks sebelum nama ortu, mis. "Putri dari" / "Daughter of".</div>

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

                                    <img src="{{ $template->getAssetUrl('bride_photo', $photoDefaults['bride_photo']) }}" alt="Bride">

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

                                <label class="form-label">Gelar Anak</label>

                                <input type="text" class="form-control" name="template_settings[groom_child_label]" value="{{ $settings['groom_child_label'] ?? 'Putra dari' }}" placeholder="Putra dari">

                                <div class="form-text">Teks sebelum nama ortu, mis. "Putra dari" / "Son of".</div>

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

                                    <img src="{{ $template->getAssetUrl('groom_photo', $photoDefaults['groom_photo']) }}" alt="Groom">

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

            @php

                // Video hasil upload disimpan di `template-assets/…`; nilai lama

                // (`assets/videos/…`, video bawaan template) bukan file upload,

                // jadi badge & tombol hapus tidak boleh menganggapnya ada.

                $heroVideoUploaded = str_starts_with((string) ($assets['hero_video'] ?? ''), 'template-assets/');

            @endphp

            <div class="settings-card">

                <div class="settings-card-header"><i class="fas fa-video me-2"></i>Video Pembuka (Hero)</div>

                <div class="settings-card-body">

                    <form id="tsHeroVideoForm" onsubmit="event.preventDefault(); window.saveSettingsForm('tsHeroVideoForm');">

                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">Video yang Diputar</label>

                                <select class="form-select" name="template_settings[hero_video_source]">

                                    <option value="default" @selected(($settings['hero_video_source'] ?? 'default') === 'default')>Video bawaan template</option>

                                    <option value="custom" @selected(($settings['hero_video_source'] ?? '') === 'custom')>Video yang saya upload</option>

                                    <option value="none" @selected(($settings['hero_video_source'] ?? '') === 'none')>Tanpa video</option>

                                </select>

                                <div class="form-text" style="font-size:.78rem;">

                                    Pilih <strong>Video yang saya upload</strong> setelah mengunggah videonya di samping.

                                    Kalau filenya belum ada, undangan memakai video bawaan. Pilihan

                                    <strong>Tanpa video</strong> membuat popup pembuka hanya memakai foto hero.

                                </div>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">Upload Video (MP4 / WebM / MOV / OGV, maks 20MB)</label>

                                <div class="asset-thumb mb-2" style="height:150px;">

                                    @if(!empty($assets['hero_video']))

                                        <video src="{{ $template->getAssetUrl('hero_video') }}" controls muted playsinline preload="metadata" style="max-height:150px;max-width:100%;"></video>

                                    @else

                                        <span class="badge-default">Belum ada file — memakai video bawaan</span>

                                    @endif

                                </div>

                                <div class="d-flex gap-1">

                                    <label class="btn btn-outline-primary btn-sm mb-0">

                                        <i class="fas fa-upload me-1"></i> Upload Video

                                        <input type="file" class="d-none"

                                            accept="video/mp4,video/webm,video/ogg,video/quicktime,video/*"

                                            onchange="window.uploadVideo(this, 'hero_video')">

                                    </label>

                                    @if($heroVideoUploaded)

                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.deleteAsset('hero_video')">

                                            <i class="fas fa-trash"></i> Hapus

                                        </button>

                                    @endif

                                </div>

                                <div class="asset-key mt-1">{{ $assets['hero_video'] ?? 'belum ada file' }}</div>

                                <div class="form-text" style="font-size:.78rem;">

                                    Format <strong>MP4 (H.264)</strong> paling aman untuk semua HP. Idealnya di bawah

                                    <strong>10MB</strong> supaya undangan tidak lama dibuka.

                                </div>

                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary-custom mt-3"><i class="fas fa-save me-1"></i> Simpan Pilihan Video</button>

                    </form>

                </div>

            </div>

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

                <div class="settings-card-header"><i class="fas fa-video me-2"></i>Video Gallery</div>

                <div class="settings-card-body">

                    <div class="row g-3">

                        <div class="col-12">

                            <label class="form-label">Link YouTube Video</label>

                            <input type="text" class="form-control" id="galleryVideoUrl" value="{{ $settings['gallery_video_url'] ?? '' }}" placeholder="https://www.youtube.com/watch?v=...">

                            <small class="text-muted">Kosongkan jika tidak ingin menampilkan video</small>

                        </div>

                    </div>

                    <button type="button" class="btn btn-primary-custom btn-sm mt-2" onclick="window.saveVideoUrl()"><i class="fas fa-save me-1"></i> Simpan Video</button>

                </div>

            </div>

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

                Setiap <strong>grup undangan</strong> punya link sendiri (<code>/slug/invitation</code>),

                daftar acara sendiri, dan daftar tamu sendiri — jadi satu undangan bisa ditujukan ke audiens

                yang berbeda. Acara <strong>Utama</strong> dipakai untuk countdown &amp; tanggal undangan,

                sedangkan acara <strong>tambahan</strong> ditampilkan berurutan di bawahnya pada section

                "Wedding Day".

            </p>



            <div class="settings-card">

                <div class="settings-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <span><i class="fas fa-layer-group me-2"></i>Grup Undangan ({{ $groups->count() }})</span>

                    <button type="button" class="btn btn-light btn-sm" onclick="window.toggleGroupForm()">

                        <i class="fas fa-plus me-1"></i> Tambah Grup

                    </button>

                </div>

                <div class="settings-card-body">

                    <p class="text-muted mb-2" style="font-size:.82rem;">

                        Grup <span class="badge bg-success">Utama</span> adalah yang tampil di link publik

                        <code>/invitation</code>. Mengubah slug membuat link lama tidak berlaku lagi.

                    </p>



                    <div id="tsNewGroupPanel" class="d-none">

                        <form id="tsNewGroupForm" onsubmit="event.preventDefault(); window.saveNewGroup();">

                            @csrf

                            <div class="ceremony-row" style="background:#fff8f6;">

                                <div class="row g-3">

                                    <div class="col-md-5">

                                        <label class="form-label">Nama Grup Undangan</label>

                                        <input type="text" class="form-control" name="group_name" data-group-name placeholder="Mis. Undangan Keluarga Bride" required>

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label">Slug URL</label>

                                        <div class="input-group">

                                            <span class="input-group-text">/</span>

                                            <input type="text" class="form-control" name="event_key" data-group-slug placeholder="otomatis-dari-nama">

                                        </div>

                                    </div>

                                    <div class="col-md-3 d-flex align-items-end">

                                        <div class="form-check">

                                            <input class="form-check-input" type="checkbox" name="is_default" value="1" id="tsNewGroupDefault">

                                            <label class="form-check-label" for="tsNewGroupDefault">Jadikan undangan utama</label>

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label">Nama Acara Utama</label>

                                        <input type="text" class="form-control" name="title" placeholder="Resepsi Pernikahan">

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label">Tanggal</label>

                                        <input type="date" class="form-control" name="event_date" required>

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label">Waktu Mulai</label>

                                        <input type="text" class="form-control" name="start_time" placeholder="08:00" required>

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label">Waktu Selesai</label>

                                        <input type="text" class="form-control" name="finish_time" placeholder="Selesai" required>

                                    </div>

                                    <div class="col-md-8">

                                        <label class="form-label">Link Google Maps</label>

                                        <input type="text" class="form-control" name="google_map_link" placeholder="https://maps.app.goo.gl/...">

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label">Nama Tempat / Lokasi</label>

                                        <input type="text" class="form-control" name="location" required>

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label">Alamat Lengkap</label>

                                        <textarea class="form-control" name="address" rows="1" placeholder="Jalan, RT/RW, Kecamatan, Kabupaten"></textarea>

                                    </div>

                                </div>

                                <div class="mt-3">

                                    <button type="submit" class="btn btn-primary-custom"><i class="fas fa-save me-1"></i> Buat Grup</button>

                                    <button type="button" class="btn btn-outline-secondary ms-2" onclick="window.toggleGroupForm()">Batal</button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>



            <div id="tsGroupList">

            @forelse($groups as $group)

                <form id="tsGroupForm{{ $group->id }}" class="ts-group-form" onsubmit="event.preventDefault(); window.saveGroupForm({{ $group->id }});">

                    @csrf

                    <input type="hidden" name="event_id" value="{{ $group->id }}">



                    <div class="settings-card">

                        <div class="settings-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <span>

                                <i class="fas fa-map-marker-alt me-2"></i>{{ $group->label }}

                                @if($group->is_default)<span class="badge bg-light text-dark ms-1">Utama</span>@endif

                            </span>

                            <span class="d-flex align-items-center gap-2">

                                <a href="{{ $group->publicUrl() }}" target="_blank" class="text-white" style="font-size:.8rem;">

                                    <code class="text-white">/{{ $group->event_key }}/invitation</code>

                                    <i class="fas fa-external-link-alt ms-1"></i>

                                </a>

                                <button type="button" class="btn btn-outline-light btn-sm" title="Hapus grup"

                                        onclick="window.deleteGroup(@js($group->event_key), @js($group->label))">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </span>

                        </div>

                        <div class="settings-card-body">

                            <div class="row g-3">

                                <div class="col-md-5">

                                    <label class="form-label">Nama Grup Undangan</label>

                                    <input type="text" class="form-control" name="group_name" value="{{ $group->label }}" required>

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">Slug URL</label>

                                    <div class="input-group">

                                        <span class="input-group-text">/</span>

                                        <input type="text" class="form-control" name="event_key" value="{{ $group->event_key }}" data-group-slug>

                                    </div>

                                    <small class="text-muted">Ubah hanya bila perlu — link lama tidak berlaku lagi.</small>

                                </div>

                                <div class="col-md-3 d-flex align-items-center">

                                    <div class="form-check">

                                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="tsGroupDefault{{ $group->id }}" @checked($group->is_default)>

                                        <label class="form-check-label" for="tsGroupDefault{{ $group->id }}">Undangan utama</label>

                                    </div>

                                </div>

                            </div>



                            <h6 class="fw-bold mt-4 mb-2" style="font-size:.85rem;">Acara Utama</h6>

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <label class="form-label">Nama Acara</label>

                                    <input type="text" class="form-control" name="title" value="{{ $group->title }}" placeholder="Resepsi Pernikahan">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">Tanggal</label>

                                    <input type="date" class="form-control" name="event_date" value="{{ $group->event_date ? \Carbon\Carbon::parse($group->event_date)->format('Y-m-d') : '' }}" required>

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">Waktu Mulai</label>

                                    <input type="text" class="form-control" name="start_time" value="{{ $group->start_time }}" placeholder="08:00" required>

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">Waktu Selesai</label>

                                    <input type="text" class="form-control" name="finish_time" value="{{ $group->finish_time }}" placeholder="Selesai" required>

                                </div>

                                <div class="col-md-8">

                                    <label class="form-label">Link Google Maps</label>

                                    <input type="text" class="form-control" name="google_map_link" value="{{ $group->google_map_link }}" placeholder="https://maps.app.goo.gl/...">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">Nama Tempat / Lokasi</label>

                                    <input type="text" class="form-control" name="location" value="{{ $group->location }}" required>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">Alamat Lengkap</label>

                                    <textarea class="form-control" name="address" rows="1" placeholder="Jalan, RT/RW, Kecamatan, Kabupaten">{{ $group->address }}</textarea>

                                </div>

                            </div>



                            <h6 class="fw-bold mt-4 mb-2" style="font-size:.85rem;">Acara Tambahan</h6>

                            <div id="tsCeremonyRows{{ $group->id }}" data-ceremony-container>

                                @foreach($group->details as $detail)

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

                            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="window.addCeremonyRow('tsCeremonyRows{{ $group->id }}')">

                                <i class="fas fa-plus me-1"></i> Tambah Acara

                            </button>



                            <div class="mt-3">

                                <button type="submit" class="btn btn-primary-custom"><i class="fas fa-save me-1"></i> Simpan {{ $group->label }}</button>

                                <span class="text-muted ms-2" style="font-size:.8rem;">

                                    {{ $group->guest_count }} tamu · {{ $group->details->count() + 1 }} acara

                                </span>

                            </div>

                        </div>

                    </div>

                </form>

            @empty

                <div class="text-center text-muted py-4">

                    <i class="fas fa-layer-group fa-2x mb-2"></i>

                    <p class="mb-0">Belum ada grup undangan. Klik <strong>Tambah Grup</strong> untuk membuat.</p>

                </div>

            @endforelse

            </div>

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



        {{-- ================= MUSIK / BACKSOUND ================= --}}

        <div class="tab-pane fade" id="ts-music">

            <form id="tsMusicForm" onsubmit="event.preventDefault(); window.saveSettingsForm('tsMusicForm');">

                @csrf

                <div class="settings-card">

                    <div class="settings-card-header"><i class="fas fa-music me-2"></i>Backsound Undangan</div>

                    <div class="settings-card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">Musik yang Diputar</label>

                                <select class="form-select" name="template_settings[backsound]">

                                    <option value="default" @selected(($settings['backsound'] ?? 'default') === 'default')>Musik bawaan template</option>

                                    <option value="custom" @selected(($settings['backsound'] ?? '') === 'custom')>File yang saya upload</option>

                                    <option value="none" @selected(($settings['backsound'] ?? '') === 'none')>Tanpa musik</option>

                                </select>

                                <div class="form-text" style="font-size:.78rem;">

                                    Pilih <strong>File yang saya upload</strong> setelah mengunggah MP3 di bawah. Kalau filenya belum ada,

                                    undangan memakai musik bawaan. Pilihan <strong>Tanpa musik</strong> menyembunyikan tombol speakernya juga.

                                </div>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">Upload Backsound (MP3 / M4A / OGG / WAV, maks 20MB)</label>

                                <div class="asset-thumb mb-2" style="height:70px;">

                                    @if(!empty($assets['backsound_file']))

                                        <span class="badge-uploaded"><i class="fas fa-check me-1"></i>File sudah diupload</span>

                                    @else

                                        <span class="badge-default">Belum ada file — memakai musik bawaan</span>

                                    @endif

                                </div>

                                <div class="d-flex gap-1">

                                    <label class="btn btn-outline-primary btn-sm mb-0">

                                        <i class="fas fa-upload me-1"></i> Upload Audio

                                        <input type="file" class="d-none"

                                            accept="audio/mpeg,audio/mp3,audio/mp4,audio/x-m4a,audio/aac,audio/ogg,audio/wav,audio/flac,audio/*"

                                            onchange="window.uploadAudio(this, 'backsound_file')">

                                    </label>

                                    @if(!empty($assets['backsound_file']))

                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.deleteAsset('backsound_file')">

                                            <i class="fas fa-trash"></i> Hapus

                                        </button>

                                    @endif

                                </div>

                                <div class="asset-key mt-1">{{ $assets['backsound_file'] ?? 'belum ada file' }}</div>

                                @if(!empty($assets['backsound_file']))

                                    <audio controls preload="none" class="w-100 mt-2" src="{{ asset('storage/'.$assets['backsound_file']) }}"></audio>

                                @endif

                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary-custom mt-3"><i class="fas fa-save me-1"></i> Simpan Pilihan Musik</button>

                    </div>

                </div>

            </form>

        </div>



        {{-- ================= HADIAH / REKENING (dinamis) ================= --}}

        <div class="tab-pane fade" id="ts-gift">

            <div class="settings-card">

                <div class="settings-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <span><i class="fas fa-gift me-2"></i>Daftar Hadiah (<span id="tsGiftCount">{{ count($gifts) }}</span>)</span>

                    <button type="button" class="btn btn-light btn-sm" onclick="window.addGiftRow()">

                        <i class="fas fa-plus me-1"></i> Tambah Hadiah

                    </button>

                </div>

                <div class="settings-card-body">

                    <p class="text-muted" style="font-size:.85rem;">

                        Tambahkan sebanyak apa pun: rekening bank, e-wallet (GoPay / OVO / Dana), atau alamat

                        pengiriman kado. Urutan mengikuti tombol ↑ ↓ dan tiap entri bisa punya logo sendiri.

                    </p>



                    <form id="tsGiftForm" onsubmit="event.preventDefault(); window.saveGifts();">

                        @csrf

                        <div id="tsGiftList">

                            @forelse($gifts as $gift)

                                @include('admin.partials.gift-row', ['gift' => $gift, 'index' => $loop->iteration])

                            @empty

                                <p class="text-muted mb-0" id="tsGiftEmpty">Belum ada entri hadiah. Klik <strong>Tambah Hadiah</strong> untuk membuat.</p>

                            @endforelse

                        </div>



                        <button type="submit" class="btn btn-primary-custom mt-3"><i class="fas fa-save me-1"></i> Simpan Data Hadiah</button>

                    </form>

                </div>

            </div>

        </div>



    </div>



    {{-- ================= OUR STORY ================= --}}

    <div class="tab-pane fade" id="ts-story">

        <div class="settings-card">

            <div class="settings-card-header d-flex justify-content-between align-items-center">

                <span><i class="fas fa-heart me-2"></i>Our Story</span>

                <button type="button" class="btn btn-sm btn-light" onclick="window.addStoryItem()"><i class="fas fa-plus me-1"></i> Tambah</button>

            </div>

            <div class="settings-card-body">

                <div class="mb-3">

                    <label class="form-label">Judul Section</label>

                    <input type="text" class="form-control" id="storyTitle" value="{{ $settings['story_title'] ?? 'Our Story' }}">

                </div>

                <div class="mb-3">

                    <label class="form-label">Subtitle</label>

                    <input type="text" class="form-control" id="storySubtitle" value="{{ $settings['story_subtitle'] ?? 'Every love story is beautiful but ours is my favorite' }}">

                </div>

                <div id="storyItemsContainer">

                    @php

                    $storyItems = json_decode($template->getSetting('story_items', '[]'), true) ?: [

                        ['title' => 'PERTEMUAN (2017)', 'description' => 'Berawal dari teman kuliah bersama-sama perjuangkan S1 Teknik Sipil, kami bertemu saat mengerjakan Tugas Besar, pertemuan itu berkembang menjadi kisah yang kami rawat perlahan'],

                        ['title' => 'LAMARAN (2026)', 'description' => 'Perjalanan kami bukanlah tanpa ujian, kami dihadapkan pada jarak yang memisahkan dan entah berapa kali kami saling memaafkan.'],

                    ];

                    @endphp

                    @foreach($storyItems as $idx => $item)

                    <div class="story-item card mb-2" data-index="{{ $idx }}">

                        <div class="card-body p-3">

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <strong style="font-size:0.85rem;">Cerita {{ $idx + 1 }}</strong>

                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.story-item').remove()"><i class="fas fa-trash"></i></button>

                            </div>

                            <input type="text" class="form-control form-control-sm mb-2 story-item-title" placeholder="Judul (misal: PERTEMUAN (2017))" value="{{ $item['title'] ?? '' }}">

                            <textarea class="form-control form-control-sm story-item-desc" rows="3" placeholder="Cerita...">{{ $item['description'] ?? '' }}</textarea>

                        </div>

                    </div>

                    @endforeach

                </div>

                <button type="button" class="btn btn-primary-custom btn-sm mt-2" onclick="window.saveStoryItems()"><i class="fas fa-save me-1"></i> Simpan Our Story</button>

            </div>

        </div>

    </div>

</div>



<template id="tsGiftRowTemplate">

    @include('admin.partials.gift-row', ['gift' => $giftTemplate, 'index' => ''])

</template>



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


        {{-- ================= SEO ================= --}}
        <div class="tab-pane fade" id="ts-seo">
            <form id="tsSeoForm" onsubmit="event.preventDefault(); window.saveSettingsForm('tsSeoForm');">
                @csrf
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-search me-2"></i>Search Engine Optimization (SEO)</div>
                    <div class="settings-card-body">
                        <p class="text-muted mb-3" style="font-size:.85rem;">
                            Atur tampilan undangan saat dibagikan di WhatsApp, Facebook, Twitter, dan mesin pencari.
                            Kosongkan semua field teks untuk menggunakan default otomatis dari nama mempelai & tanggal acara.
                        </p>

                        @php
                            $seoFields = [
                                'seo_meta_title' => [
                                    'label' => 'Meta Title',
                                    'help' => 'Judul yang muncul di tab browser & hasil pencarian. Maks 70 karakter.',
                                    'default' => $seoDefaults['title'],
                                    'tag' => 'input',
                                ],
                                'seo_meta_description' => [
                                    'label' => 'Meta Description',
                                    'help' => 'Deskripsi singkat untuk Google & share preview. Maks 160 karakter.',
                                    'default' => $seoDefaults['description'],
                                    'tag' => 'textarea',
                                ],
                                'seo_og_title' => [
                                    'label' => 'OG Title (WhatsApp / Facebook / Twitter)',
                                    'help' => 'Judul khusus untuk kartu preview saat link dibagikan. Maks 70 karakter.',
                                    'default' => $seoDefaults['og_title'],
                                    'tag' => 'input',
                                ],
                                'seo_og_description' => [
                                    'label' => 'OG Description (WhatsApp / Facebook / Twitter)',
                                    'help' => 'Deskripsi singkat pada kartu preview. Maks 200 karakter.',
                                    'default' => $seoDefaults['og_description'],
                                    'tag' => 'textarea',
                                ],
                            ];
                        @endphp

                        @foreach ($seoFields as $seoKey => $seoInfo)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-baseline gap-2 flex-wrap">
                                    <label class="form-label mb-0" for="seo-field-{{ $seoKey }}">{{ $seoInfo['label'] }}</label>
                                    <button type="button"
                                        class="btn btn-link btn-sm seo-default-btn p-0"
                                        data-seo-key="{{ $seoKey }}"
                                        onclick="window.seoUseDefault(this)"
                                        style="font-size:.75rem; color:#e44d26; text-decoration:none;">
                                        <i class="fas fa-fill-drip me-1"></i>Gunakan Default
                                    </button>
                                </div>
                                @if ($seoInfo['tag'] === 'input')
                                    <input type="text" class="form-control" id="seo-field-{{ $seoKey }}"
                                        name="template_settings[{{ $seoKey }}]"
                                        value="{{ $settings[$seoKey] ?? '' }}"
                                        placeholder="{{ $seoInfo['default'] }}"
                                        data-seo-default="{{ $seoInfo['default'] }}"
                                        maxlength="70">
                                @else
                                    <textarea class="form-control" id="seo-field-{{ $seoKey }}"
                                        name="template_settings[{{ $seoKey }}]" rows="3"
                                        placeholder="{{ $seoInfo['default'] }}"
                                        data-seo-default="{{ $seoInfo['default'] }}"
                                        maxlength="200">{{ $settings[$seoKey] ?? '' }}</textarea>
                                @endif
                                <div class="form-text">{{ $seoInfo['help'] }}</div>

                                {{-- Preview card: ini teks persisnya yang akan muncul di
                                     WhatsApp/Google kalau fieldnya dikosongkan. --}}
                                @if (! ($settings[$seoKey] ?? ''))
                                    <div class="seo-preview-card">
                                        <div class="seo-preview-badge">Tampil saat ini (default)</div>
                                        <div class="seo-preview-text">{{ $seoInfo['default'] }}</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        @php
                            $ogSelected = $settings['seo_og_image_asset'] ?? 'cover_photo';
                            $ogKeys = [
                                'cover_photo' => 'Cover Sampul (Buka Undangan)',
                                'bride_photo' => 'Foto Mempelai Wanita',
                                'groom_photo' => 'Foto Mempelai Pria',
                                'hero_photo' => 'Foto Hero / Journey',
                                'desktop_cover' => 'Cover Depan Desktop',
                                'closing_image' => 'Foto Penutup',
                                'story_image' => 'Foto Our Story',
                                'seo_og_image' => 'Upload Sendiri',
                            ];
                            $ogHasUpload = ! empty($assets['seo_og_image']);
                            if ($ogSelected === 'seo_og_image' && ! $ogHasUpload) {
                                $ogSelected = 'cover_photo';
                            }
                        @endphp

                        <input type="hidden" name="template_settings[seo_og_image_asset]" id="seoOgImageInput" value="{{ $ogSelected }}">

                        <div class="og-picker-grid" id="seoOgGrid">
                            @foreach ($ogKeys as $ogKey => $ogLabel)
                                @php
                                    $ogUrl = $ogKey === 'seo_og_image'
                                        ? $template->getAssetUrl('seo_og_image')
                                        : $template->getAssetUrl($ogKey, $photoDefaults[$ogKey] ?? null);
                                @endphp
                                <button type="button"
                                    class="og-picker-item{{ $ogSelected === $ogKey ? ' selected' : '' }}"
                                    data-og-key="{{ $ogKey }}"
                                    onclick="window.seoOgSelect('{{ $ogKey }}')"
                                    title="Pilih jadi OG image">
                                    <span class="og-picker-check"><i class="fas fa-check"></i></span>
                                    @if ($ogKey === 'seo_og_image')
                                        <span class="og-picker-badge">
                                            <span class="badge-uploaded">{{ $ogHasUpload ? 'sudah diupload' : 'belum ada file' }}</span>
                                        </span>
                                    @endif
                                    <div class="og-picker-thumb">
                                        @if (! empty($ogUrl))
                                            <img src="{{ $ogUrl }}" alt="{{ $ogLabel }}" loading="lazy">
                                        @else
                                            <span class="og-picker-empty">
                                                <i class="fas fa-image"></i> belum ada foto
                                            </span>
                                        @endif
                                    </div>
                                    <div class="og-picker-label">{{ $ogLabel }}</div>
                                </button>
                            @endforeach
                        </div>

                        <div class="form-text">
                            Klik salah satu foto untuk jadi OG image (gambar saat link dibagikan di
                            WhatsApp/media sosial). Ukuran ideal: 1200×630px. Belum ada yang cocok?
                            Upload foto khusus lewat kartu <strong>Upload Sendiri</strong> di bawah ini.
                        </div>

                        {{-- Area upload khusus OG image (selalu tampil, tanpa perlu pilih dropdown). --}}
                        <div id="seoOgUploadArea" class="mt-2">
                            <div class="d-flex gap-1 align-items-center">
                                <label class="btn btn-outline-primary btn-sm mb-0">
                                    <i class="fas fa-upload me-1"></i> Upload OG Image Baru
                                    <input type="file" class="d-none" accept="image/jpeg,image/png,image/webp,image/gif" onchange="window.uploadAsset(this, 'seo_og_image')">
                                </label>
                                @if($ogHasUpload)
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.deleteAsset('seo_og_image')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom mt-2"><i class="fas fa-save me-1"></i> Simpan SEO</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Inline <script> gak akan jalan: pane ini di-inject lewat innerHTML
             (dashboard switchTab/refreshSettingsPane), dan browser skip tag
             <script> hasil innerHTML. Klik di-handle langsung di markup. --}}

