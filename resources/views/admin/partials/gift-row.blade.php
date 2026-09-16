{{-- One gift entry (bank account, e-wallet or shipping address).

     Rendered for every saved entry and re-used as the client-side blueprint
     (`#tsGiftRowTemplate`) for new rows, so both paths always look the same.
     `window.addGiftRow()` assigns a fresh id and renames the fields to
     gifts[n][...] on submit. --}}
@php
    $logoPath = $template->getGiftLogoUrl($gift);
    $uploadedLogo = ! empty($gift['id']) ? ($assets['gift_logo_'.$gift['id']] ?? null) : null;
    $defaultLogoPath = $gift['default_logo'] ?? null;
    $defaultLogoUrl = $defaultLogoPath
        ? (str_starts_with($defaultLogoPath, 'template-assets/')
            ? asset('storage/'.$defaultLogoPath)
            : asset($defaultLogoPath))
        : '';
@endphp
<div class="ceremony-row" data-gift-row data-default-logo-url="{{ $defaultLogoUrl }}">
    <input type="hidden" data-field="id" value="{{ $gift['id'] }}">
    <input type="hidden" data-field="default_logo" value="{{ $defaultLogoPath }}">

    <div class="row g-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <label class="form-label mb-0">Hadiah #<span data-gift-number>{{ $index }}</span></label>
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-outline-secondary btn-sm" title="Naikkan" onclick="window.moveGiftRow(this, -1)"><i class="fas fa-arrow-up"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" title="Turunkan" onclick="window.moveGiftRow(this, 1)"><i class="fas fa-arrow-down"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.removeGiftRow(this)"><i class="fas fa-times"></i> Hapus</button>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label">Tipe</label>
            <select class="form-select form-select-sm" data-field="type" onchange="window.syncGiftRowFields(this)">
                <option value="bank" @selected($gift['type'] === 'bank')>Rekening Bank</option>
                <option value="ewallet" @selected($gift['type'] === 'ewallet')>E-Wallet</option>
                <option value="address" @selected($gift['type'] === 'address')>Alamat Kado</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Nama / Judul</label>
            <input type="text" class="form-control form-control-sm" data-field="label" value="{{ $gift['label'] }}" placeholder="Bank BNI / GoPay / KIRIM KADO">
        </div>

        <div class="col-md-5" data-gift-field="number">
            <label class="form-label">Nomor Rekening / E-Wallet</label>
            <input type="text" class="form-control form-control-sm" data-field="number" value="{{ $gift['number'] }}" placeholder="1234567890">
        </div>

        <div class="col-md-4">
            <label class="form-label">Atas Nama</label>
            <input type="text" class="form-control form-control-sm" data-field="holder" value="{{ $gift['holder'] }}" placeholder="Nama pemilik">
        </div>

        <div class="col-md-8" data-gift-field="address">
            <label class="form-label">Alamat Pengiriman Kado</label>
            <textarea class="form-control form-control-sm" data-field="address" rows="2" placeholder="Jalan, RT/RW, Kecamatan, Kabupaten">{{ $gift['address'] }}</textarea>
        </div>

        <div class="col-12" data-gift-field="logo">
            <label class="form-label">Logo / Gambar (opsional)</label>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="asset-thumb" style="height:60px;min-width:130px;">
                    <img data-gift-logo-preview src="{{ $logoPath }}" alt="{{ $gift['label'] }}" style="max-height:58px;" class="{{ $logoPath ? '' : 'd-none' }}">
                </div>
                <div class="d-flex gap-1">
                    <label class="btn btn-outline-primary btn-sm mb-0">
                        <i class="fas fa-upload me-1"></i> Upload
                        <input type="file" class="d-none" accept="image/jpeg,image/png,image/webp,image/gif"
                               onchange="window.uploadGiftLogo(this)">
                    </label>
                    <button type="button" class="btn btn-outline-danger btn-sm {{ $uploadedLogo ? '' : 'd-none' }}"
                            data-gift-logo-delete onclick="window.deleteGiftLogo(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
