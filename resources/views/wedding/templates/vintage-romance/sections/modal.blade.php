<div id="modal" class="modalx">
    <div class="overlayy"></div>
    <div class="content-modalx">
        <div class="info_modalx">
            <div class="modalx-logo">
                <img src="{{ asset($template->getAsset('logo_image', 'assets/vintage/logo.png')) }}" alt="Logo" class="img-fluid">
            </div>
            <p class="modalx-subtitle">WEDDING INVITATION</p>
            <h2 class="modalx-couple">{{ $guestData->couple_name ?? 'Isabel & Jefry' }}</h2>
            <p class="modalx-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d . m . Y') }}</p>
            <p class="modalx-to">Kepada Yth.</p>
            <p class="modalx-guest">{{ $guestData->name ?? 'Tamu Undangan' }}</p>
            <button id="open-invitation" class="modalx-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 2L11 13"></path>
                    <path d="M22 2L15 22L11 13L2 9L22 2Z"></path>
                </svg>
                BUKA UNDANGAN
            </button>
        </div>
    </div>
</div>
