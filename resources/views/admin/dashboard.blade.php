<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Wedding {{ $activeTemplate->getSetting('groom_name', 'Mempelai Pria') }} & {{ $activeTemplate->getSetting('bride_name', 'Mempelai Wanita') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">

    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
</head>
<body>
<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">
            <i class="fas fa-grip me-2"></i>Dashboard
        </a>
        <div class="navbar-nav ms-auto">
            <a href="{{ route('logout') }}" class="nav-link text-white"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 sidebar p-0" id="sidebar">
            <div class="p-3">
                <div class="text-center mb-4 mt-3">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-user text-white fs-5"></i>
                    </div>
                    <h6 class="mt-2 mb-0">Admin</h6>
                    <small class="text-muted">{{ $activeTemplate->getSetting('groom_name', 'Mempelai Pria') }} & {{ $activeTemplate->getSetting('bride_name', 'Mempelai Wanita') }}</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#dashboard" onclick="event.preventDefault(); event.stopPropagation(); switchTab('#dashboard');">
                            <i class="fas fa-chart-pie"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#guests" onclick="event.preventDefault(); event.stopPropagation(); switchTab('#guests');">
                            <i class="fas fa-users"></i> Tamu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#messages" onclick="event.preventDefault(); event.stopPropagation(); switchTab('#messages');">
                            <i class="fas fa-comments"></i> Ucapan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#settings" onclick="event.preventDefault(); event.stopPropagation(); switchTab('#settings');">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#wedding-templates" onclick="event.preventDefault(); event.stopPropagation(); switchTab('#wedding-templates');">
                            <i class="fas fa-palette"></i> Template
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 p-4">
            <!-- Pull to refresh indicator -->
            <div class="pull-to-refresh" id="pullToRefresh">
                <i class="fas fa-sync-alt me-2"></i> Tarik ke bawah untuk memperbarui
            </div>

            <div class="tab-content">
                <!-- Dashboard Tab -->
                <div class="tab-pane fade show active" id="dashboard">
                    <!-- Quick Stats -->
                    <div class="row mb-4 mobile-stats">
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-number text-primary" id="totalGuests">{{ $stats['total_guests'] }}
                                </div>
                                <div class="stat-label">Tamu Hadir</div>
                                <small class="text-muted">Total <span id="allGuestsCount">{{ $stats['all_guests_count'] }}</span>
                                    tamu</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-number text-success" id="totalPeople">{{ $stats['total_people'] }}
                                </div>
                                <div class="stat-label">Total Orang</div>
                                <small class="text-muted">Estimasi tamu hadir</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-number text-info" id="totalMessages">{{ $stats['total_messages'] }}
                                </div>
                                <div class="stat-label">Ucapan</div>
                                <small class="text-muted">Pesan dari tamu</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-number text-warning" id="pendingGuests">{{ $stats['pending_guests']
                                    }}
                                </div>
                                <div class="stat-label">Belum Konfirm</div>
                                <small class="text-muted">Menunggu respon</small>
                            </div>
                        </div>
                    </div>

                    <!-- Event Statistics — satu kartu per grup undangan (dinamis) -->
                    @php
                        $groupPalette = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-secondary', 'bg-dark'];
                        $groupColors = $groups->values()->mapWithKeys(function ($group, $index) use ($groupPalette) {
                            return [$group->event_key => $groupPalette[$index % count($groupPalette)]];
                        });
                    @endphp
                    <div class="row mb-4">
                        @forelse($eventStats as $groupSlug => $eventStat)
                        @php
                            $groupProgress = $eventStat['all_guests_count'] > 0
                                ? (($eventStat['attending_guests'] + $eventStat['not_attending_guests']) / $eventStat['all_guests_count']) * 100
                                : 0;
                        @endphp
                        <div class="col-md-6 mb-3">
                            <div class="event-card">
                                <div class="event-header {{ $loop->odd ? 'gedung' : 'rumah' }}">
                                    <h5 class="mb-1">
                                        <i class="fas fa-{{ $loop->odd ? 'building' : 'home' }} me-2"></i>{{ $eventStat['name'] }}
                                        @if($eventStat['is_default'])
                                            <span class="badge bg-light text-dark ms-1">Utama</span>
                                        @endif
                                    </h5>
                                    <small>
                                        <span id="event-{{ $groupSlug }}-all">{{ $eventStat['all_guests_count'] }}</span> tamu diundang
                                        · <a class="text-white-50" href="{{ url($groupSlug.'/invitation') }}"
                                             target="_blank">/{{ $groupSlug }}/invitation</a>
                                    </small>
                                </div>
                                <div class="event-stats">
                                    <div class="row text-center">
                                        <div class="col-4 event-stat">
                                            <div class="event-number text-primary" id="event-{{ $groupSlug }}-attending">{{ $eventStat['total_guests'] }}</div>
                                            <div class="event-label">Tamu Hadir</div>
                                        </div>
                                        <div class="col-4 event-stat">
                                            <div class="event-number text-success" id="event-{{ $groupSlug }}-people">{{ $eventStat['guest_attends_total'] }}</div>
                                            <div class="event-label">Total Orang</div>
                                        </div>
                                        <div class="col-4 event-stat">
                                            <div class="event-number text-info" id="event-{{ $groupSlug }}-messages">{{ $eventStat['total_messages'] }}</div>
                                            <div class="event-label">Ucapan</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">Progress Konfirmasi</small>
                                        <div class="progress progress-custom mt-1">
                                            <div class="progress-bar bg-success" style="width: {{ $groupProgress }}%"
                                                 id="event-{{ $groupSlug }}-progress"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 mb-3">
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Belum ada grup undangan. Buat grupnya di <strong>Settings → Acara</strong>.
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div> <!-- /dashboard tab-pane -->

                <!-- Settings Tab -->
                <div class="tab-pane fade" id="settings">
                    <div id="settingsContent" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Memuat pengaturan...</p>
                    </div>
                </div>

                <!-- Guests Tab -->
                <div class="tab-pane fade" id="guests">
                    <!-- Add Guest Card -->
                    <div class="add-guest-card">
                        <div class="event-card">
                            <div class="p-3">
                                <h5 class="mb-3"><i class="fas fa-plus-circle me-2"></i>Tambah Tamu Baru</h5>
                                <form id="addGuestForm">
                                    @csrf
                                    <div class="row g-2">
                                        <div class="col-md-3 col-12">
                                            <input type="text" name="name" class="form-control form-control-sm"
                                                   placeholder="Nama tamu" required id="guestNameInput">
                                            <div class="form-text text-danger d-none" id="nameError">
                                                Nama sudah terdaftar untuk acara ini
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <input type="text" name="whatsapp_number" class="form-control form-control-sm"
                                                   placeholder="Nomor WhatsApp (opsional)" id="whatsappInput">
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <select name="guest_attends" class="form-control form-control-sm">
                                                <option value="1">1 Orang</option>
                                                <option value="2">2 Orang</option>
                                                <option value="3">3 Orang</option>
                                                <option value="4">4 Orang</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <select name="event_type" class="form-control form-control-sm" required
                                                    id="eventTypeSelect">
                                                @foreach($groups as $groupOption)
                                                    <option value="{{ $groupOption->event_key }}">{{ $groupOption->label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <button type="submit" class="btn btn-primary-custom w-100"
                                                    id="submitGuestBtn">
                                                <i class="fas fa-plus me-1"></i> Tambah
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="event-card">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 col-12 mb-2 mb-md-0">
                                            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Tamu</h5>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="row g-2">
                                                <div class="col-md-3 col-12">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                        <input type="text" class="form-control" id="searchFilter" placeholder="Cari nama...">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <select class="form-control form-control-sm" id="eventFilter">
                                                        <option value="all">Semua Grup</option>
                                                        @foreach($groups as $groupOption)
                                                            <option value="{{ $groupOption->event_key }}">{{ $groupOption->label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <select class="form-control form-control-sm" id="statusFilter">
                                                        <option value="all">Semua Status</option>
                                                        <option value="Hadir">Hadir</option>
                                                        <option value="Tidak Hadir">Tidak Hadir</option>
                                                        <option value="Belum Konfirmasi">Belum Konfirmasi</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <button class="btn btn-outline-secondary w-100" id="resetFilter">
                                                        <i class="fas fa-refresh me-1"></i> Reset
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4><i class="fas fa-users me-2"></i>Manage Tamu</h4>
                        <div>
                            <button class="btn btn-primary-custom me-2" id="manageTemplate">
                                <i class="fas fa-envelope me-1"></i> Template
                            </button>
                            <button class="btn btn-primary-custom me-2" id="refreshGuests">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </button>
                            <button class="btn btn-primary-custom me-2" id="exportFiltered">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                        </div>
                    </div>

                    <!-- Guest Count Info -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info py-2">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Menampilkan <strong id="filteredCount">{{ count($guests) }}</strong> dari <strong id="totalCount">{{ count($guests) }}</strong> tamu
                                    <span id="filterInfo" class="ms-2"></span>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="table-custom">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 d-none d-md-table">
                                <thead>
                                <tr>
                                    <th>Nama Tamu</th>
                                    <th>Grup Undangan</th>
                                    <th>WhatsApp</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Diupdate</th>
                                    <th>Link</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody id="guestsTableBody">
                                @foreach($guests as $guest)
                                @php
                                $guestGroupSlug = $guest->event ? $guest->event->event_key : ($groups->first()?->event_key ?? '');
                                $invitationUrl = $guestGroupSlug
                                    ? url("/{$guestGroupSlug}/invitation?to=" . urlencode($guest->name))
                                    : url('/invitation');
                                @endphp
                                <tr data-guest-id="{{ $guest->id }}"
                                    data-event-type="{{ $guest->event ? $guest->event->event_key : '' }}"
                                    data-attendance="{{ $guest->attendance ?? 'Belum Konfirmasi' }}"
                                    data-whatsapp="{{ $guest->whatsapp_number }}">
                                    <td>
                                        <strong>{{ $guest->name }}</strong>
                                        <br><small class="text-muted">{{ $guest->code }}</small>
                                    </td>
                                    <td>
                                        @if($guest->event)
                                        <span class="badge {{ $groupColors[$guest->event->event_key] ?? 'bg-primary' }} badge-custom">
                                                    {{ $guest->event->label }}
                                                </span>
                                        @else
                                        <span class="badge bg-secondary badge-custom">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($guest->whatsapp_number)
                                        <small>{{ $guest->whatsapp_number }}</small>
                                        @else
                                        <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>{{ $guest->guest_attends }} orang</td>
                                    <td>
                                        @if($guest->attendance === 'Hadir')
                                        <span class="badge bg-success badge-custom">Hadir</span>
                                        @elseif($guest->attendance === 'Tidak Hadir')
                                        <span class="badge bg-danger badge-custom">Tidak Hadir</span>
                                        @else
                                        <span class="badge bg-warning badge-custom">Belum Konfirmasi</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">
                                            {{ $guest->is_opened ? 'Dibuka' : 'Belum dibuka' }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $guest->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $guest->updated_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-sm btn-whatsapp share-guest-whatsapp"
                                                    data-name="{{ $guest->name }}"
                                                    data-event='@json($guest->event)'
                                                    data-number="{{ $guest->formatted_whatsapp_number }}"
                                                    title="Share via WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </button>
                                            <button class="btn btn-outline-primary copy-link"
                                                    data-url="{{ $invitationUrl }}"
                                                    title="Copy Link">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <a href="{{ $invitationUrl }}" target="_blank"
                                               class="btn btn-outline-info" title="Preview">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="guest-share-actions">
                                            <button class="btn btn-sm btn-edit edit-guest"
                                                    data-id="{{ $guest->id }}"
                                                    data-name="{{ $guest->name }}"
                                                    data-guest-attends="{{ $guest->guest_attends }}"
                                                    data-event-type="{{ $guest->event ? $guest->event->event_key : '' }}"
                                                    data-attendance="{{ $guest->attendance ?? 'Belum Konfirmasi' }}"
                                                    data-whatsapp="{{ $guest->whatsapp_number }}"
                                                    title="Edit Tamu">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-guest"
                                                    data-id="{{ $guest->id }}"
                                                    data-name="{{ $guest->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <!-- Mobile View -->
                            <div class="d-md-none" id="mobileGuestsList">
                                @foreach($guests as $guest)
                                @php
                                $guestGroupSlug = $guest->event ? $guest->event->event_key : ($groups->first()?->event_key ?? '');
                                $invitationUrl = $guestGroupSlug
                                    ? url("/{$guestGroupSlug}/invitation?to=" . urlencode($guest->name))
                                    : url('/invitation');
                                @endphp
                                <div class="card mb-3" data-guest-id="{{ $guest->id }}"
                                     data-event-type="{{ $guest->event ? $guest->event->event_key : '' }}"
                                     data-attendance="{{ $guest->attendance ?? 'Belum Konfirmasi' }}"
                                     data-whatsapp="{{ $guest->whatsapp_number }}">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $guest->name }}</h6>
                                        <p class="card-text mb-1">
                                            <small class="text-muted">Kode: {{ $guest->code }}</small>
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>Acara:</strong>
                                            @if($guest->event)
                                            <span class="badge {{ $groupColors[$guest->event->event_key] ?? 'bg-primary' }} badge-custom">
                                                        {{ $guest->event->label }}
                                                    </span>
                                            @else
                                            <span class="badge bg-secondary badge-custom">-</span>
                                            @endif
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>WhatsApp:</strong> {{ $guest->whatsapp_number ?? '-' }}
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>Jumlah:</strong> {{ $guest->guest_attends }} orang
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>Status:</strong>
                                            @if($guest->attendance === 'Hadir')
                                            <span class="badge bg-success badge-custom">Hadir</span>
                                            @elseif($guest->attendance === 'Tidak Hadir')
                                            <span class="badge bg-danger badge-custom">Tidak Hadir</span>
                                            @else
                                            <span class="badge bg-warning badge-custom">Belum Konfirmasi</span>
                                            @endif
                                            <small class="text-muted">({{ $guest->is_opened ? 'Dibuka' : 'Belum dibuka' }})</small>
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>Dibuat:</strong> <small class="text-muted">{{ $guest->created_at->format('d/m/Y H:i') }}</small>
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>Diupdate:</strong> <small class="text-muted">{{ $guest->updated_at->format('d/m/Y H:i') }}</small>
                                        </p>
                                        <div class="btn-group w-100 mt-2">
                                            <button class="btn btn-sm btn-whatsapp share-guest-whatsapp"
                                                    data-name="{{ $guest->name }}"
                                                    data-event='@json($guest->event)'
                                                    data-number="{{ $guest->formatted_whatsapp_number }}"
                                                    title="Share via WhatsApp">
                                                <i class="fab fa-whatsapp"></i> Share
                                            </button>
                                            <button class="btn btn-outline-primary copy-link"
                                                    data-url="{{ $invitationUrl }}"
                                                    title="Copy Link">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                            <a href="{{ $invitationUrl }}" target="_blank"
                                               class="btn btn-outline-info" title="Preview">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </div>
                                        <div class="btn-group w-100 mt-2">
                                            <button class="btn btn-sm btn-edit edit-guest"
                                                    data-id="{{ $guest->id }}"
                                                    data-name="{{ $guest->name }}"
                                                    data-guest-attends="{{ $guest->guest_attends }}"
                                                    data-event-type="{{ $guest->event ? $guest->event->event_key : '' }}"
                                                    data-attendance="{{ $guest->attendance ?? 'Belum Konfirmasi' }}"
                                                    data-whatsapp="{{ $guest->whatsapp_number }}"
                                                    title="Edit Tamu">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-guest"
                                                    data-id="{{ $guest->id }}"
                                                    data-name="{{ $guest->name }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages Tab -->
                <div class="tab-pane fade" id="messages">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4><i class="fas fa-comments me-2"></i>Ucapan & Doa</h4>
                        <button class="btn btn-primary-custom" id="refreshMessages">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                    </div>

                    <div class="table-custom">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>Pengirim</th>
                                    <th>Pesan</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody id="messagesTableBody">
                                @foreach($messages as $message)
                                <tr data-message-id="{{ $message->id }}">
                                    <td>
                                        <strong>{{ $message->name }}</strong>
                                        @if($message->guest)
                                        <br><small class="text-muted">
                                            {{ $message->created_at->format('d/m/Y') }}<br>
                                            {{ $message->created_at->format('H:i') }}
                                        </small>
                                        @endif
                                    </td>
                                    <td>{{ $message->message ?: '-' }}</td>
                                    <td>
                                        <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger delete-message"
                                                data-id="{{ $message->id }}"
                                                data-name="{{ $message->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Wedding Templates Tab -->
                <div class="tab-pane fade" id="wedding-templates">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4><i class="fas fa-palette me-2"></i>Wedding Templates</h4>
                        <div>
                            <a href="#settings" class="btn btn-warning me-2" style="color:white;" onclick="event.preventDefault(); switchTab('#settings');">
                                <i class="fas fa-cog me-1"></i> Pengaturan Template
                            </a>
                            <button class="btn btn-primary-custom" onclick="location.reload();">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Active Template Banner -->
                    @if($activeTemplate)
                    <div class="alert alert-success d-flex align-items-center justify-content-between mb-3 alert-limited" role="alert">
                        <div>
                            <strong><i class="fas fa-check-circle me-1"></i> Template Aktif:</strong> {{ $activeTemplate->name }}
                            <span class="badge bg-success ms-2">{{ $activeTemplate->slug }}</span>
                        </div>
                        <div>
                            <a href="{{ route('wedding.public') }}" target="_blank" class="btn btn-sm btn-outline-success me-2">
                                <i class="fas fa-eye me-1"></i> Lihat Undangan
                            </a>
                            <a href="#settings" class="btn btn-sm btn-success" onclick="event.preventDefault(); switchTab('#settings');">
                                <i class="fas fa-edit me-1"></i> Edit Pengaturan
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning d-flex align-items-center justify-content-between mb-3 alert-limited" role="alert">
                        <div>
                            <strong><i class="fas fa-exclamation-triangle me-1"></i> Belum ada template aktif.</strong> Aktifkan salah satu template di bawah ini.
                        </div>
                    </div>
                    @endif

                    <div class="row" id="weddingTemplatesContainer">
                        @forelse($templates as $tpl)
                        @php
                            $isActive = $tpl->is_active;
                            $cardBorder = $isActive ? 'border-success border-2' : '';
                            $cardShadow = $isActive ? 'box-shadow: 0 4px 15px rgba(16,185,129,0.2);' : '';
                        @endphp
                        <div class="col-md-4 mb-4" data-template-id="{{ $tpl->id }}">
                            <div class="card h-100 {{ $cardBorder }} tpl-card" @if($cardShadow) style="{{ $cardShadow }}" @endif>
                                <div class="tpl-thumb-wrap">
                                    <img src="{{ $tpl->thumbnail ?: '/assets/images/gallery/slide1.jpg' }}" class="card-img-top tpl-thumb" alt="{{ $tpl->name }}">
                                    @if($isActive)
                                    <div style="position:absolute;top:10px;right:10px;">
                                        <span class="badge bg-success tpl-badge-active"><i class="fas fa-check-circle me-1"></i> Template Aktif</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="card-body tpl-card-body">
                                    <h5 class="card-title tpl-card-title">
                                        {{ $tpl->name }}
                                        @if($isActive)
                                        <span class="badge bg-success ms-2"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                        @endif
                                    </h5>
                                    <p class="card-text tpl-card-desc">{{ $tpl->description ?: 'Tidak ada deskripsi' }}</p>
                                    <p class="card-text"><small class="text-muted"><i class="fas fa-link me-1"></i>{{ $tpl->slug }}</small></p>
                                </div>
                                <div class="card-footer tpl-card-footer">
                                    <div class="d-flex gap-2">
                                        @if(!$isActive)
                                        <button class="btn btn-sm btn-success flex-grow-1 activate-wedding-template tpl-btn" data-id="{{ $tpl->id }}">
                                            <i class="fas fa-check me-1"></i> Aktifkan
                                        </button>
                                        @else
                                        <span class="btn btn-sm btn-success flex-grow-1 tpl-btn" disabled style="opacity:0.8;">
                                            <i class="fas fa-check-circle me-1"></i> Sedang Aktif
                                        </span>
                                        @endif
                                        <a href="{{ route('wedding.public') }}" target="_blank" class="btn btn-sm btn-outline-info tpl-btn" title="Preview Undangan">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#settings" class="btn btn-sm btn-outline-warning tpl-btn" title="Pengaturan Template" onclick="event.preventDefault(); switchTab('#settings');">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada template. Buat template baru untuk memulai.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<div class="mobile-bottom-nav">
    <a href="#dashboard" class="mobile-nav-item active" onclick="event.preventDefault(); event.stopPropagation(); switchTab('#dashboard');">
        <i class="fas fa-chart-pie"></i>
        <span>Dashboard</span>
    </a>
    <a href="#guests" class="mobile-nav-item" onclick="event.preventDefault(); event.stopPropagation(); switchTab('#guests');">
        <i class="fas fa-users"></i>
        <span>Tamu</span>
    </a>
    <a href="#messages" class="mobile-nav-item" onclick="event.preventDefault(); event.stopPropagation(); switchTab('#messages');">
        <i class="fas fa-comments"></i>
        <span>Ucapan</span>
    </a>
    <a href="#settings" class="mobile-nav-item" onclick="event.preventDefault(); event.stopPropagation(); switchTab('#settings');">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
    </a>
    <a href="#wedding-templates" class="mobile-nav-item" onclick="event.preventDefault(); event.stopPropagation(); switchTab('#wedding-templates');">
        <i class="fas fa-palette"></i>
        <span>Template</span>
    </a>
</div>

<!-- Modal for Share Options -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-custom">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share Undangan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Tamu</label>
                    <input type="text" class="form-control" id="modalGuestName" readonly>
                    <input type="hidden" id="modalWhatsappNumber" readonly>
                </div>

                <!-- Template Selection -->
                <div class="mb-3">
                    <label class="form-label">Pilih Template</label>
                    <select class="form-control" id="templateSelect">
                        <option value="">Loading template...</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Link Undangan</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="modalInvitationLink" readonly>
                        <button class="btn btn-outline-primary" id="modalCopyLink">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Preview Pesan</label>
                    <textarea class="form-control" id="modalMessageTemplate" rows="6" readonly></textarea>
                </div>

                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Variabel yang tersedia: <code>{guest_name}</code>, <code>{groom_name}</code>,
                        <code>{bride_name}</code>, <code>{event_date}</code>, <code>{event_time}</code>,
                        <code>{event_location}</code>, <code>{invitation_link}</code>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-whatsapp" id="modalShareWhatsApp">
                    <i class="fab fa-whatsapp me-1"></i> Share via WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Manage Templates -->
<div class="modal fade" id="manageTemplatesModal" tabindex="-1" aria-labelledby="manageTemplatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal-custom">
            <div class="modal-header">
                <h5 class="modal-title" id="manageTemplatesModalLabel">
                    <i class="fas fa-envelope me-2"></i>Kelola Template WhatsApp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Tambah Template -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-plus me-2"></i>Tambah Template Baru</h6>
                    </div>
                    <div class="card-body">
                        <form id="addTemplateForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Template</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                                            <label class="form-check-label" for="isActive">Aktif</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_default" id="isDefault">
                                            <label class="form-check-label" for="isDefault">Jadikan Default</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Template Pesan</label>
                                <textarea class="form-control" name="template" rows="8" required
                                          placeholder="Gunakan variabel: {guest_name}, {groom_name}, {bride_name}, {event_date}, {event_time}, {event_location}, {invitation_link}"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-save me-1"></i> Simpan Template
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Daftar Template -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Template</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="templatesTable">
                                <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Default</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody id="templatesTableBody">
                                <!-- Template list akan diisi via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Edit Guest -->
<div class="modal fade" id="editGuestModal" tabindex="-1" aria-labelledby="editGuestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-custom">
            <div class="modal-header">
                <h5 class="modal-title" id="editGuestModalLabel">Edit Data Tamu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editGuestForm">
                    @csrf
                    <input type="hidden" name="guest_id" id="editGuestId">
                    <div class="mb-3">
                        <label class="form-label">Nama Tamu</label>
                        <input type="text" class="form-control" id="editGuestName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Tamu</label>
                        <select class="form-control" id="editGuestAttends" name="guest_attends">
                            <option value="1">1 Orang</option>
                            <option value="2">2 Orang</option>
                            <option value="3">3 Orang</option>
                            <option value="4">4 Orang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Acara</label>
                        <select class="form-control" id="editEventType" name="event_type" required>
                            @foreach($groups as $groupOption)
                                <option value="{{ $groupOption->event_key }}">{{ $groupOption->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor WhatsApp (opsional)</label>
                        <input type="text" class="form-control" id="editWhatsappNumber"
                               name="whatsapp_number" placeholder="Contoh: 081234567890">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Konfirmasi</label>
                        <select class="form-control" id="editAttendance" name="attendance">
                            <option value="Belum Konfirmasi">Belum Konfirmasi</option>
                            <option value="Hadir">Hadir</option>
                            <option value="Tidak Hadir">Tidak Hadir</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary-custom" id="saveEditGuest">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Elegant Confirmation Modal -->
<div class="modal fade" id="elegantConfirmModal" tabindex="-1" aria-labelledby="elegantConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <div class="confirm-icon mb-3">
                    <i class="fas fa-exclamation-circle text-warning"></i>
                </div>
                <h5 class="modal-title mb-3" id="elegantConfirmModalLabel">Konfirmasi</h5>
                <p class="text-muted mb-4" id="confirmMessage">Apakah Anda yakin ingin melanjutkan?</p>

                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-lg btn-outline-secondary flex-fill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="button" class="btn btn-lg btn-primary flex-fill" id="confirmActionBtn">
                        <i class="fas fa-check me-2"></i>Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast Modal -->
<div class="modal fade" id="successToastModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-success text-white">
            <div class="modal-body text-center p-4">
                <div class="success-icon mb-3">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <h5 class="mb-2">Berhasil!</h5>
                <p class="mb-0" id="successMessage">Operasi berhasil dilakukan</p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Global toast helper. It must live outside $(document).ready() because the
    // settings-embed handlers below (window.saveSettingsForm, window.uploadAsset,
    // window.uploadGallery, ...) and switchTab() all call it from top-level scope.
    function showToast(message, type = 'info', delay = null) {
        var container = document.getElementById('toastContainer');
        if (!container) return;
        var bgClass = type === 'success' ? 'bg-success' :
            type === 'error' ? 'bg-danger' :
                type === 'warning' ? 'bg-warning' : 'bg-info';
        var safe = String(message)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\n/g, '<br>');
        var el = document.createElement('div');
        el.className = 'toast align-items-center text-white ' + bgClass + ' border-0';
        el.setAttribute('role', 'alert');
        el.innerHTML = '<div class="d-flex"><div class="toast-body">' + safe +
            '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
        container.appendChild(el);
        new bootstrap.Toast(el, { delay: delay || (type === 'error' ? 8000 : 3000) }).show();
        el.addEventListener('hidden.bs.toast', function() { el.remove(); });
    }
    window.showToast = showToast;

    function switchTab(target) {
        if (!target || !target.startsWith('#')) return;
        // Only touch the dashboard's own nav/tabs — the embedded template settings
        // has its own .nav-link/.tab-pane and must keep its active tab state.
        var inSettings = function(el) { return el.closest('#settingsContent') !== null; };
        document.querySelectorAll('.nav-link').forEach(function(el) { if (!inSettings(el)) el.classList.remove('active'); });
        document.querySelectorAll('.mobile-nav-item').forEach(function(el) { el.classList.remove('active'); });
        document.querySelectorAll('.nav-link[href="' + target + '"]').forEach(function(el) { if (!inSettings(el)) el.classList.add('active'); });
        document.querySelectorAll('.mobile-nav-item[href="' + target + '"]').forEach(function(el) { el.classList.add('active'); });
        document.querySelectorAll('.tab-pane').forEach(function(el) { if (!inSettings(el)) el.classList.remove('show', 'active'); });
        var pane = document.querySelector(target);
        if (pane) { pane.classList.add('show', 'active'); }

        // Load settings content on first visit
        if (target === '#settings' && !document.getElementById('settingsContent').dataset.loaded) {
            fetch('{{ route("admin.template-settings.embed") }}')
                .then(r => r.text())
                .then(html => {
                    document.getElementById('settingsContent').innerHTML = html;
                    document.getElementById('settingsContent').dataset.loaded = '1';
                    initSettingsGallerySort();
                    initSettingsEvents();
                    initSettingsGifts();
                })
                .catch(() => {
                    document.getElementById('settingsContent').innerHTML = '<div class="text-center py-5 text-danger"><i class="fas fa-exclamation-triangle fa-2x mb-2"></i><p>Gagal memuat pengaturan</p></div>';
                });
        }

    }

    // ==================== INVITATION GROUPS ====================
    // Every group is dynamic; it is identified by its URL slug (`event_key`).
    // These maps drive the guest table, the filters and the WhatsApp link.
    let groupLabels = @json($groups->mapWithKeys(function ($group) {
        return [$group->event_key => $group->label];
    }));
    let groupColors = @json($groupColors);
    let defaultGroupSlug = @json($groups->firstWhere('is_default', true)?->event_key ?? ($groups->first()?->event_key ?? ''));

    function escapeAttrValue(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    // Groups live in the Settings pane but are consumed by the guest tab, so
    // after a group is created/renamed/deleted the dropdowns are rebuilt from
    // the server list instead of requiring a page reload.
    function refreshGroupOptions() {
        fetch('{{ route("admin.template-settings.groups.index") }}', {
            headers: { 'Accept': 'application/json' }
        })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (!d.success || !d.groups) return;

                const palette = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-secondary', 'bg-dark'];
                const labels = {};
                const colors = {};

                d.groups.forEach(function(group, index) {
                    labels[group.slug] = group.label;
                    colors[group.slug] = palette[index % palette.length];
                });

                groupLabels = labels;
                groupColors = colors;

                const defaultGroup = d.groups.filter(function(group) { return group.is_default; })[0];
                defaultGroupSlug = defaultGroup ? defaultGroup.slug : (d.groups[0] ? d.groups[0].slug : '');

                syncGroupSelects(d.groups);
            })
            .catch(function() {});
    }
    window.refreshGroupOptions = refreshGroupOptions;

    function syncGroupSelects(groups) {
        const targets = [
            { selector: '#eventFilter', allLabel: 'Semua Grup' },
            { selector: '#eventTypeSelect' },
            { selector: '#editEventType' }
        ];

        targets.forEach(function(target) {
            const select = document.querySelector(target.selector);
            if (!select) return;

            const previous = select.value;
            let html = target.allLabel ? '<option value="all">' + target.allLabel + '</option>' : '';

            html += groups.map(function(group) {
                return '<option value="' + escapeAttrValue(group.slug) + '">' + escapeAttrValue(group.label) + '</option>';
            }).join('');

            select.innerHTML = html;

            if (previous && select.querySelector('option[value="' + previous + '"]')) {
                select.value = previous;
            }
        });
    }

    // ==================== SETTINGS EMBED JS ====================
    function settingsTab(btn) {
        var target = btn.getAttribute('data-bs-target');
        var container = document.getElementById('settingsContent');
        if (!container) return;
        container.querySelectorAll('.nav-link').forEach(function(t) { t.classList.remove('active'); });
        container.querySelectorAll('.tab-pane').forEach(function(p) { p.classList.remove('show', 'active'); p.style.display = 'none'; });
        btn.classList.add('active');
        var pane = container.querySelector(target);
        if (pane) { pane.classList.add('show', 'active'); pane.style.display = ''; }
    }
    window.settingsTab = settingsTab;

    // ==================== IN-PLACE PANE REFRESH ====================
    // Uploads/deletes used to call location.reload(), which sent the admin back
    // to the Dashboard home tab instead of staying in Settings. Now the embed
    // HTML is re-fetched, swapped in, and the tab that was open is restored.
    function activeSettingsTabTarget() {
        var container = document.getElementById('settingsContent');
        if (!container) return null;
        var link = container.querySelector('.nav-link.active[data-bs-target]');
        return link ? link.getAttribute('data-bs-target') : null;
    }

    function activateSettingsTab(target) {
        var container = document.getElementById('settingsContent');
        if (!container || !target) return;
        var btn = container.querySelector('.nav-link[data-bs-target="' + target + '"]');
        if (btn) settingsTab(btn);
    }

    function refreshSettingsPane(target) {
        var container = document.getElementById('settingsContent');
        if (!container) { setTimeout(function() { location.reload(); }, 600); return; }

        var keep = target ? ('#' + String(target).replace(/^#/, '')) : activeSettingsTabTarget();

        fetch('{{ route("admin.template-settings.embed") }}')
            .then(function(r) { return r.text(); })
            .then(function(html) {
                container.innerHTML = html;
                container.dataset.loaded = '1';
                activateSettingsTab(keep);
                initSettingsGallerySort();
                initSettingsEvents();
                initSettingsGifts();
            })
            .catch(function() { setTimeout(function() { location.reload(); }, 600); });
    }
    window.refreshSettingsPane = refreshSettingsPane;

    // ==================== MULTI-ACARA (EXTRA CEREMONIES) ====================
    function ceremonyRows(containerId) {
        var container = document.getElementById(containerId);
        return container ? Array.prototype.slice.call(container.querySelectorAll('[data-ceremony-row]')) : [];
    }

    function paintCeremonyNumbers(containerId) {
        ceremonyRows(containerId).forEach(function(row, position) {
            var label = row.querySelector('[data-ceremony-number]');
            if (label) label.textContent = position + 1;
        });
    }

    function initSettingsEvents() {
        // Numbering is painted per group card, so groups created from Settings —
        // or any group added later — are handled automatically.
        document.querySelectorAll('[data-ceremony-container]').forEach(function(container) {
            paintCeremonyNumbers(container.id);
        });

        // The group name fills in the URL slug until the slug is edited by hand.
        var newGroupForm = document.getElementById('tsNewGroupForm');
        if (newGroupForm) {
            bindGroupSlugAutoFill(
                newGroupForm.querySelector('[data-group-name]'),
                newGroupForm.querySelector('[data-group-slug]')
            );
        }
    }
    window.initSettingsEvents = initSettingsEvents;

    window.addCeremonyRow = function(containerId) {
        var container = document.getElementById(containerId);
        var tpl = document.getElementById('tsCeremonyRowTemplate');
        if (!container || !tpl) return;
        container.appendChild(tpl.content.cloneNode(true));
        paintCeremonyNumbers(containerId);
        var rows = ceremonyRows(containerId);
        var last = rows[rows.length - 1];
        if (!last) return;
        var firstField = last.querySelector('[data-field="title"]');
        if (firstField) firstField.focus();
        if (last.scrollIntoView) last.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    };

    window.removeCeremonyRow = function(btn) {
        var row = btn.closest ? btn.closest('[data-ceremony-row]') : null;
        if (!row) return;
        var container = row.parentNode;
        row.remove();
        if (container && container.id) paintCeremonyNumbers(container.id);
    };

    // Names are (re)assigned from the DOM order right before submit, so removing
    // a middle ceremony can never leave a gap or a duplicate details[] index.
    function reindexCeremonyRows(form) {
        var index = 0;
        form.querySelectorAll('[data-ceremony-row]').forEach(function(row) {
            row.querySelectorAll('[data-field]').forEach(function(field) {
                field.name = 'details[' + index + '][' + field.getAttribute('data-field') + ']';
            });
            index++;
        });
    }

    // ==================== UPLOAD HELPERS ====================
    // Phone photos are routinely 4-12MB while the server only accepts ~8MB, so
    // every image is downscaled/re-encoded in the browser first. GIFs are sent
    // untouched to preserve their animation.
    var UPLOAD_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
    var UPLOAD_MAX_DIMENSION = 2400;
    var UPLOAD_QUALITY = 0.82;

    function isSupportedImage(file) {
        return UPLOAD_TYPES.indexOf((file.type || '').toLowerCase()) !== -1;
    }

    function compressForUpload(file, maxDimension, quality) {
        maxDimension = maxDimension || UPLOAD_MAX_DIMENSION;
        quality = quality || UPLOAD_QUALITY;

        return new Promise(function(resolve) {
            // Never re-encode GIF (would lose the animation).
            if (!/^image\/(jpeg|jpg|png|webp)$/.test((file.type || '').toLowerCase())) {
                return resolve(file);
            }

            var objectUrl = URL.createObjectURL(file);
            var img = new Image();

            img.onload = function() {
                URL.revokeObjectURL(objectUrl);
                var w = img.naturalWidth, h = img.naturalHeight;
                var scale = Math.min(1, maxDimension / Math.max(w, h));

                // Nothing to gain: already small enough
                if (scale === 1 && file.size <= 1200 * 1024) return resolve(file);

                var canvas = document.createElement('canvas');
                canvas.width = Math.max(1, Math.round(w * scale));
                canvas.height = Math.max(1, Math.round(h * scale));
                var ctx = canvas.getContext('2d');

                var outputType = file.type === 'image/png' ? 'image/png' : 'image/jpeg';
                if (outputType === 'image/jpeg') {
                    // JPEG has no alpha channel — paint a white background first
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                }
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                canvas.toBlob(function(blob) {
                    if (!blob || blob.size >= file.size) return resolve(file);
                    var extension = outputType === 'image/png' ? '.png' : '.jpg';
                    var name = file.name.replace(/\.[^.]+$/, '') + extension;
                    resolve(new File([blob], name, { type: outputType, lastModified: Date.now() }));
                }, outputType, outputType === 'image/png' ? 1 : quality);
            };

            img.onerror = function() { URL.revokeObjectURL(objectUrl); resolve(file); };
            img.src = objectUrl;
        });
    }

    function readJsonResponse(r) {
        return r.text().then(function(text) {
            var data = null;
            try { data = JSON.parse(text); } catch (e) { data = null; }

            if (data) return { ok: r.ok, status: r.status, data: data };

            var snippet = text.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim().slice(0, 200);
            var hint = r.status === 419 ? 'Sesi login berakhir — muat ulang halaman lalu coba lagi.'
                : r.status === 413 ? 'File terlalu besar untuk dikirim ke server.'
                : r.status === 401 || r.status === 403 ? 'Tidak punya akses — login ulang.'
                : r.status >= 500 ? 'Terjadi error di server. Cek storage/logs/laravel.log.'
                : 'Coba lagi atau perkecil fotonya.';

            // Surface the raw server message so a failure can be diagnosed from
            // the toast alone (e.g. "Maximum execution time exceeded").
            if (snippet) hint += '\nPesan server: ' + snippet;

            return { ok: false, status: r.status, data: null, hint: hint };
        });
    }

    function uploadHint(result) {
        if (result && result.data) {
            var err = result.data.message || 'Gagal upload';
            var errors = result.data.errors;
            if (errors) {
                err += '\n' + (Array.isArray(errors) ? errors.join('\n') : Object.values(errors).flat().join('\n'));
            }
            return err;
        }
        return 'Gagal upload (HTTP ' + (result ? result.status : '?') + '). ' + ((result && result.hint) || '');
    }

    window.uploadAsset = async function(input, assetKey) {
        var file = input.files[0];
        input.value = '';
        if (!file) return;

        if (!isSupportedImage(file)) {
            showToast('Format "' + (file.type || 'tidak dikenal') + '" tidak didukung.\nGunakan JPG, PNG, WebP, atau GIF (HEIC dari iPhone harus dikonversi dulu).', 'error');
            return;
        }

        try {
            var prepared = await compressForUpload(file);
            if (prepared !== file) {
                showToast('Foto diperkecil otomatis: ' + formatFileSizeShort(file.size) + ' → ' + formatFileSizeShort(prepared.size), 'info');
            }

            var fd = new FormData();
            fd.append('asset_key', assetKey);
            fd.append('file', prepared);

            var r = await fetch('{{ route("admin.template-settings.upload-asset") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: fd
            });

            var result = await readJsonResponse(r);

            if (result.data && result.data.success) {
                var m = 'Foto berhasil diupload!';
                if (result.data.width && result.data.height) m += ' (' + result.data.width + 'x' + result.data.height + 'px)';
                if (result.data.warnings && result.data.warnings.length) { m += '\n' + result.data.warnings.join('\n'); showToast(m, 'warning'); }
                else showToast(m);
                // Stay in Settings — refresh only the pane, keeping the open tab.
                refreshSettingsPane();
            } else {
                showToast(uploadHint(result), 'error');
            }
        } catch (e) {
            showToast('Upload gagal: ' + (e && e.message ? e.message : 'kesalahan tidak diketahui'), 'error');
        }
    };

    function formatFileSizeShort(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(0) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }
    window.compressForUpload = compressForUpload;
    window.isSupportedImage = isSupportedImage;

    // Backsound: file audio dikirim apa adanya (kompresi gambar tidak berlaku),
    // jadi validasinya hanya format + ukuran sebelum dikirim ke endpoint audio.
    var AUDIO_EXTENSIONS = ['mp3', 'm4a', 'm4b', 'aac', 'ogg', 'oga', 'wav', 'flac'];
    var AUDIO_MAX_BYTES = 20 * 1024 * 1024;

    window.uploadAudio = async function(input, assetKey) {
        var file = input.files[0];
        input.value = '';
        if (!file) return;

        var ext = (file.name.split('.').pop() || '').toLowerCase();
        var typeOk = (file.type || '').indexOf('audio/') === 0 || (file.type || '').indexOf('application/ogg') === 0;

        if (AUDIO_EXTENSIONS.indexOf(ext) === -1 && !typeOk) {
            showToast('Format "' + (file.type || ext || 'tidak dikenal') + '" tidak didukung.\nGunakan MP3, M4A, AAC, OGG, WAV, atau FLAC.', 'error');
            return;
        }

        if (file.size > AUDIO_MAX_BYTES) {
            showToast('Ukuran file audio maksimal 20MB. File ini ' + formatFileSizeShort(file.size) + '. Kompres dulu filenya.', 'error');
            return;
        }

        try {
            var fd = new FormData();
            fd.append('asset_key', assetKey);
            fd.append('file', file);

            var r = await fetch('{{ route("admin.template-settings.upload-audio") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: fd
            });

            var result = await readJsonResponse(r);

            if (result.data && result.data.success) {
                showToast('Backsound berhasil diupload (' + formatFileSizeShort(result.data.size || file.size) + ')!');
                refreshSettingsPane();
            } else {
                showToast(uploadHint(result), 'error');
            }
        } catch (e) {
            showToast('Upload gagal: ' + (e && e.message ? e.message : 'kesalahan tidak diketahui'), 'error');
        }
    };

    // Video pembuka (hero): file dikirim apa adanya. Ukurannya jauh lebih
    // besar dari foto/audio, jadi format + ukuran diperiksa dulu di browser
    // supaya tidak menunggu upload penuh hanya untuk ditolak server.
    var VIDEO_EXTENSIONS = ['mp4', 'm4v', 'webm', 'ogv', 'mov'];
    var VIDEO_MAX_BYTES = 20 * 1024 * 1024;

    window.uploadVideo = async function(input, assetKey) {
        var file = input.files[0];
        input.value = '';
        if (!file) return;

        var ext = (file.name.split('.').pop() || '').toLowerCase();
        var typeOk = (file.type || '').indexOf('video/') === 0;

        if (VIDEO_EXTENSIONS.indexOf(ext) === -1 && !typeOk) {
            showToast('Format "' + (file.type || ext || 'tidak dikenal') + '" tidak didukung.\nGunakan MP4 (H.264), WebM, OGV, atau MOV.', 'error');
            return;
        }

        if (file.size > VIDEO_MAX_BYTES) {
            showToast('Ukuran video maksimal 20MB. File ini ' + formatFileSizeShort(file.size) + '. Kompres dulu ke MP4 720p.', 'error');
            return;
        }

        try {
            var fd = new FormData();
            fd.append('asset_key', assetKey);
            fd.append('file', file);

            showToast('Mengunggah video ' + formatFileSizeShort(file.size) + '… jangan tutup halaman ini.', 'info');

            var r = await fetch('{{ route("admin.template-settings.upload-video") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: fd
            });

            var result = await readJsonResponse(r);

            if (result.data && result.data.success) {
                showToast('Video pembuka berhasil diupload (' + formatFileSizeShort(result.data.size || file.size) + ')!');

                if (result.data.warnings && result.data.warnings.length) {
                    showToast(result.data.warnings.join('\n'), 'warning');
                }

                refreshSettingsPane();
            } else {
                showToast(uploadHint(result), 'error');
            }
        } catch (e) {
            showToast('Upload gagal: ' + (e && e.message ? e.message : 'kesalahan tidak diketahui'), 'error');
        }
    };

    window.deleteAsset = async function(ak) {
        if (!confirm('Yakin ingin menghapus asset ini?')) return;
        try {
            var r = await fetch('{{ route("admin.template-settings.delete-asset") }}', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ asset_key: ak })
            });
            var j = await r.json();
            if (j.success) {
                showToast('Asset berhasil dihapus!');
                refreshSettingsPane();
            } else {
                showToast(j.message || 'Gagal hapus', 'error');
            }
        } catch(e) {
            showToast('Error menghapus asset', 'error');
        }
    };

    // OG image picker: klik salah satu kartu foto untuk jadi gambar share.
    // Dipanggil inline dari template-settings-content (pane di-inject via
    // innerHTML, jadi tag <script> embed gak jalan — handler harus global).
    var ogPickerLabels = {
        'cover_photo': 'Cover Sampul',
        'bride_photo': 'Foto Mempelai Wanita',
        'groom_photo': 'Foto Mempelai Pria',
        'hero_photo': 'Foto Hero',
        'desktop_cover': 'Cover Depan Desktop',
        'closing_image': 'Foto Penutup',
        'story_image': 'Foto Our Story',
        'seo_og_image': 'Upload Sendiri'
    };
    window.seoOgSelect = function(key) {
        var grid = document.getElementById('seoOgGrid');
        if (!grid) return;
        grid.querySelectorAll('.og-picker-item').forEach(function(btn) {
            btn.classList.toggle('selected', btn.getAttribute('data-og-key') === key);
        });
        var input = document.getElementById('seoOgImageInput');
        if (input) input.value = key;
        showToast('OG image dipilih: ' + (ogPickerLabels[key] || key) + '. Klik Simpan SEO untuk menerapkan.', 'info', 3500);
    };

    // SEO field "Gunakan Default": isi field dengan teks default yang
    // dipakai kalau fieldnya kosong, lalu sembunyikan preview card-nya.
    window.seoUseDefault = function(btn) {
        var key = btn.getAttribute('data-seo-key');
        var field = document.getElementById('seo-field-' + key);
        if (!field) return;
        field.value = field.getAttribute('data-seo-default') || '';
        field.dispatchEvent(new Event('input'));
        var card = field.parentElement.querySelector('.seo-preview-card');
        if (card) card.remove();
        showToast('Field diisi dengan teks default. Klik Simpan SEO untuk menerapkan.', 'info', 3500);
    };

    // NOTE: the endpoint expects `files[]` (see TemplateSettingController@uploadGallery),
    // not `gallery[]` — sending the wrong key made every dashboard upload fail with 422.
    // Each photo is compressed in the browser first (same reason as uploadAsset).
    window.uploadGallery = async function(input) {
        var selected = Array.prototype.slice.call(input.files || []);
        input.value = '';
        if (!selected.length) return;

        var unsupported = selected.filter(function(f) { return !isSupportedImage(f); });
        var files = selected.filter(isSupportedImage);

        if (unsupported.length) {
            showToast(unsupported.length + ' file dilewati (format tidak didukung):\n' + unsupported.map(function(f) { return f.name; }).join('\n'), 'warning');
        }
        if (!files.length) return;

        try {
            showToast('Memproses ' + files.length + ' foto...', 'info', 2000);

            var prepared = [];
            for (var i = 0; i < files.length; i++) {
                prepared.push(await compressForUpload(files[i]));
            }

            var totalBefore = files.reduce(function(a, f) { return a + f.size; }, 0);
            var totalAfter = prepared.reduce(function(a, f) { return a + f.size; }, 0);

            // Send in batches: one huge request can exceed the server's
            // post_max_size (25M) and fail with 413 "no file received".
            var MAX_BATCH_BYTES = 12 * 1024 * 1024;
            var batches = [];
            var batch = [];
            var batchSize = 0;
            prepared.forEach(function(f) {
                if (batchSize + f.size > MAX_BATCH_BYTES && batch.length) {
                    batches.push(batch);
                    batch = [];
                    batchSize = 0;
                }
                batch.push(f);
                batchSize += f.size;
            });
            if (batch.length) batches.push(batch);

            var uploaded = 0;
            var failed = 0;
            var notes = [];

            for (var b = 0; b < batches.length; b++) {
                if (batches.length > 1) {
                    showToast('Mengupload batch ' + (b + 1) + ' dari ' + batches.length + ' (' + formatFileSizeShort(totalAfter) + ' total)...', 'info', 2500);
                } else {
                    showToast('Mengupload ' + prepared.length + ' foto (' + formatFileSizeShort(totalBefore) + ' → ' + formatFileSizeShort(totalAfter) + ')...', 'info', 2500);
                }

                var fd = new FormData();
                batches[b].forEach(function(f) {
                    fd.append('files[]', f);
                    fd.append('caption[]', '');
                });

                var result = await readJsonResponse(await fetch('{{ route("admin.template-settings.upload-gallery") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: fd
                }));

                if (result.data && result.data.success) {
                    uploaded += (result.data.images || []).length || batches[b].length;
                    if (result.data.warnings && result.data.warnings.length) {
                        notes = notes.concat(result.data.warnings);
                    }
                } else {
                    failed += batches[b].length;
                    notes.push(uploadHint(result));
                }
            }

            if (uploaded > 0) {
                var summary = uploaded + ' foto berhasil diupload' + (failed ? ', ' + failed + ' gagal' : '');
                if (notes.length) summary += '\n' + notes.slice(0, 6).join('\n');
                showToast(summary, failed || notes.length ? 'warning' : 'success');
                refreshSettingsPane('#ts-gallery');
            } else {
                showToast(notes.length ? notes.join('\n') : 'Upload gallery gagal', 'error');
            }
        } catch (e) {
            showToast('Upload gallery gagal: ' + (e && e.message ? e.message : 'kesalahan tidak diketahui'), 'error');
        }
    };

    // Gallery items are addressed by their storage path, not their position:
    // after a drag-and-drop reorder the numeric index in the page is stale, so
    // delete and caption always send `path`.
    window.deleteGallery = function(path) {
        if (!confirm('Hapus foto ini?')) return;
        fetch('{{ route("admin.template-settings.gallery.delete") }}', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ path: path || null })
        }).then(function(r) { return r.json(); }).then(function(d) {
            if (!d.success) { showToast(d.message || 'Gagal hapus', 'error'); return; }
            showToast('Foto dihapus');

            // Remove the tile in place. Reload only for the empty state, which
            // is rendered server side.
            var grid = document.getElementById('tsGalleryGrid');
            var item = (path && grid)
                ? grid.querySelector('.gallery-item[data-path="' + CSS.escape(path) + '"]')
                : null;

            if (!item) { refreshSettingsPane('#ts-gallery'); return; }

            item.remove();
            paintSettingsGalleryOrder(grid);
            var remaining = grid.querySelectorAll('.gallery-item').length;
            var countBadge = document.getElementById('tsGalleryCount');
            var tabCount = document.getElementById('tsGalleryTabCount');
            if (countBadge) countBadge.textContent = remaining + ' foto';
            if (tabCount) tabCount.textContent = remaining;
            if (remaining === 0) refreshSettingsPane('#ts-gallery');
        }).catch(function() { showToast('Gagal menghapus foto', 'error'); });
    };

    window.editGalleryCaption = function(current, path) {
        var caption = prompt('Caption foto:', current || '');
        if (caption === null) return;
        fetch('{{ route("admin.template-settings.gallery.caption") }}', {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ caption: caption, path: path || null })
        }).then(function(r) { return r.json(); }).then(function(d) {
            if (d.success) {
                var item = document.querySelector('#tsGalleryGrid .gallery-item[data-path="' + CSS.escape(path) + '"] .gallery-item-caption');
                if (item) item.textContent = caption;
                showToast('Caption disimpan');
            } else {
                showToast(d.message || 'Gagal menyimpan caption', 'error');
            }
        }).catch(function() { showToast('Gagal menyimpan caption', 'error'); });
    };

    // ==================== GALLERY DRAG-AND-DROP REORDER ====================
    // Pointer events are used instead of the HTML5 drag API so that reordering
    // also works on phones/tablets (touch), not just with a mouse.
    function settingsGalleryItems(grid) {
        return Array.prototype.slice.call(grid.querySelectorAll('.gallery-item'));
    }

    function paintSettingsGalleryOrder(grid) {
        settingsGalleryItems(grid).forEach(function(item, position) {
            var badge = item.querySelector('.order-badge');
            if (badge) badge.textContent = position + 1;
        });
    }

    function flashSettingsGallerySaved() {
        var el = document.getElementById('tsGallerySaved');
        if (!el) return;
        el.style.opacity = '1';
        clearTimeout(el._timer);
        el._timer = setTimeout(function() { el.style.opacity = '0'; }, 2000);
    }

    function saveSettingsGalleryOrder() {
        var grid = document.getElementById('tsGalleryGrid');
        if (!grid) return;
        var order = settingsGalleryItems(grid).map(function(item) { return item.dataset.path; });
        fetch('{{ route("admin.template-settings.gallery.reorder") }}', {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ order: order })
        }).then(function(r) { return r.json(); }).then(function(d) {
            if (d.success) { flashSettingsGallerySaved(); showToast('Urutan gallery disimpan'); }
            else showToast(d.message || 'Gagal menyimpan urutan', 'error');
        }).catch(function() { showToast('Gagal menyimpan urutan gallery', 'error'); });
    }

    function initSettingsGallerySort() {
        var grid = document.getElementById('tsGalleryGrid');
        if (!grid) return;
        if (grid.dataset.sortReady === '1') { paintSettingsGalleryOrder(grid); return; }
        grid.dataset.sortReady = '1';
        paintSettingsGalleryOrder(grid);

        // Ghost ditempel di dalam `.ts-wrap` supaya gaya `.ts-wrap .gallery-item`
        // tetap berlaku (kalau ditempel ke <body>, style-nya tidak ikut).
        var wrap = grid.closest('.ts-wrap') || document.body;

        var dragged = null;   // kartu asli yang sedang diurutkan (tetap di grid)
        var ghost = null;     // salinan melayang yang mengikuti kursor
        var grabOffsetX = 0;
        var grabOffsetY = 0;
        var dragOver = null;  // kartu acuan sisip, untuk indikator drop
        var orderBefore = ''; // urutan saat drag dimulai

        function currentOrder() {
            return settingsGalleryItems(grid).map(function (item) { return item.dataset.path; }).join('\n');
        }

        function setDragOver(target) {
            if (dragOver === target) return;
            if (dragOver) dragOver.classList.remove('drag-over');
            dragOver = target;
            if (dragOver) dragOver.classList.add('drag-over');
        }

        function removeGhost() {
            if (ghost && ghost.parentNode) ghost.parentNode.removeChild(ghost);
            ghost = null;
        }

        function finishDrag() {
            if (!dragged) return;

            var changed = currentOrder() !== orderBefore;

            setDragOver(null);
            removeGhost();
            dragged.classList.remove('dragging');
            dragged = null;
            document.body.style.userSelect = '';
            document.body.classList.remove('is-sorting-gallery');

            // Hanya kirim ke server kalau urutannya benar-benar berubah, jadi
            // klik di handle tanpa menggeser tidak memicu request/notifikasi.
            if (changed) saveSettingsGalleryOrder();
        }

        function moveGhost(clientX, clientY) {
            if (!ghost) return;
            ghost.style.left = (clientX - grabOffsetX) + 'px';
            ghost.style.top = (clientY - grabOffsetY) + 'px';
        }

        // Auto-scroll saat pointer mendekati tepi viewport supaya daftar panjang
        // tetap bisa diurutkan tanpa melepas drag.
        function autoScroll(clientY) {
            var edge = 80;
            if (clientY < edge) window.scrollBy(0, -18);
            else if (clientY > window.innerHeight - edge) window.scrollBy(0, 18);
        }

        // Pindahkan `dragged` ke sebelah `target`, mengikuti posisi pointer.
        // Grid mengalir kiri→kanan lalu turun baris: kalau pointer masih dekat
        // tengah kartu (satu baris) dipakai sumbu horizontal, kalau sudah
        // melewati baris dipakai sumbu vertikal.
        function moveOver(target, clientX, clientY) {
            if (!target || target === dragged || target.parentNode !== grid) return;

            setDragOver(target);

            var rect = target.getBoundingClientRect();
            var relX = clientX - (rect.left + rect.width / 2);
            var relY = clientY - (rect.top + rect.height / 2);
            var after = Math.abs(relY) > rect.height * 0.25 ? relY > 0 : relX > 0;

            // Lewati kalau kartu sudah ada di posisi itu (menghindari DOM churn
            // yang bikin drag terasa bergetar).
            var items = settingsGalleryItems(grid);
            var from = items.indexOf(dragged);
            var to = items.indexOf(target) + (after ? 1 : 0);
            if (from > -1 && to > -1 && (from < to ? to - 1 : to) === from) return;

            grid.insertBefore(dragged, after ? target.nextElementSibling : target);
            paintSettingsGalleryOrder(grid);
        }

        settingsGalleryItems(grid).forEach(function(item) {
            var handle = item.querySelector('.drag-handle');
            if (!handle) return;

            handle.addEventListener('pointerdown', function(e) {
                if (e.button !== undefined && e.button !== 0) return;
                e.preventDefault();

                dragged = item;
                orderBefore = currentOrder();
                var rect = item.getBoundingClientRect();
                grabOffsetX = e.clientX - rect.left;
                grabOffsetY = e.clientY - rect.top;

                // Salinan melayang supaya kartu terlihat terangkat mengikuti
                // kursor. `pointer-events: none` agar elemen di bawah kursor
                // tetap kartu grid asli, bukan ghost-nya.
                ghost = item.cloneNode(true);
                ghost.classList.add('drag-ghost');
                ghost.removeAttribute('id');
                ghost.style.position = 'fixed';
                ghost.style.left = rect.left + 'px';
                ghost.style.top = rect.top + 'px';
                ghost.style.width = rect.width + 'px';
                ghost.style.height = rect.height + 'px';
                ghost.style.margin = '0';
                ghost.style.zIndex = '9999';
                ghost.style.pointerEvents = 'none';
                ghost.style.opacity = '.9';
                wrap.appendChild(ghost);

                item.classList.add('dragging');
                document.body.style.userSelect = 'none';
                document.body.classList.add('is-sorting-gallery');

                try { handle.setPointerCapture(e.pointerId); } catch (err) { }
            });

            handle.addEventListener('pointermove', function(e) {
                if (!dragged || dragged !== item) return;
                e.preventDefault();

                moveGhost(e.clientX, e.clientY);
                autoScroll(e.clientY);

                var under = document.elementFromPoint(e.clientX, e.clientY);
                var target = under && under.closest ? under.closest('#tsGalleryGrid .gallery-item') : null;
                if (target) moveOver(target, e.clientX, e.clientY);
            });

            handle.addEventListener('pointerup', finishDrag);
            handle.addEventListener('pointercancel', finishDrag);
            handle.addEventListener('lostpointercapture', finishDrag);
        });
    }
    window.initSettingsGallerySort = initSettingsGallerySort;

    // ==================== INVITATION GROUPS ====================
    // Groups are dynamic: every group owns a URL slug, a ceremony list and its
    // own guest list, so the same invitation can target different audiences.
    function slugifyGroupName(value) {
        return String(value || '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    function bindGroupSlugAutoFill(nameInput, slugInput) {
        if (!nameInput || !slugInput) return;

        slugInput.addEventListener('input', function() {
            slugInput.dataset.manual = '1';
        });

        nameInput.addEventListener('input', function() {
            if (slugInput.dataset.manual === '1') return;
            slugInput.value = slugifyGroupName(nameInput.value);
        });
    }

    window.toggleGroupForm = function() {
        var panel = document.getElementById('tsNewGroupPanel');
        if (!panel) return;

        panel.classList.toggle('d-none');

        if (!panel.classList.contains('d-none')) {
            var nameInput = panel.querySelector('input[name="group_name"]');
            if (nameInput) nameInput.focus();
            if (panel.scrollIntoView) panel.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }
    };

    function submitGroupForm(url, formData) {
        return fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: formData
        }).then(function(r) {
            // 419/413/500 answers are not JSON — report the status instead of a
            // generic "something went wrong".
            return r.json().catch(function() {
                return { success: false, message: 'Respons server tidak valid (HTTP ' + r.status + ')' };
            });
        }).then(function(d) {
            if (d.success) {
                showToast(d.message || 'Data grup undangan disimpan');
                refreshGroupOptions();
                refreshSettingsPane('#ts-events');
            } else {
                var err = d.message || 'Gagal menyimpan';
                if (d.errors) err += '\n' + Object.values(d.errors).flat().join('\n');
                showToast(err, 'error');
            }
        }).catch(function() { showToast('Terjadi kesalahan', 'error'); });
    }

    window.saveNewGroup = function() {
        var form = document.getElementById('tsNewGroupForm');
        if (!form) return;
        if (!form.reportValidity()) return;

        submitGroupForm('{{ route("admin.template-settings.groups.store") }}', new FormData(form));
    };

    // Save one group card: name, slug, default flag and its ceremonies.
    window.saveGroupForm = function(groupId) {
        var form = document.getElementById('tsGroupForm' + groupId);
        if (!form) return;

        reindexCeremonyRows(form);

        var fd = new FormData(form);
        fd.append('_method', 'PUT');
        submitGroupForm('{{ route("admin.template-settings.events.update") }}', fd);
    };

    window.deleteGroup = function(slug, label) {
        if (!window.confirm('Hapus grup undangan "' + label + '"?\n\nAcara di dalamnya ikut terhapus dan link /' + slug + '/invitation tidak berlaku lagi. Tamu yang masih terdaftar di grup ini harus dipindahkan atau dihapus dulu.')) {
            return;
        }

        var fd = new FormData();
        fd.append('_method', 'DELETE');
        fd.append('event_key', slug);

        fetch('{{ route("admin.template-settings.groups.destroy") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: fd
        }).then(function(r) {
            return r.json().catch(function() {
                return { success: false, message: 'Respons server tidak valid (HTTP ' + r.status + ')' };
            });
        }).then(function(d) {
            if (d.success) {
                showToast(d.message || 'Grup undangan dihapus');
                refreshGroupOptions();
                refreshSettingsPane('#ts-events');
            } else {
                showToast(d.message || 'Gagal menghapus grup', 'error');
            }
        }).catch(function() { showToast('Terjadi kesalahan', 'error'); });
    };

    // ==================== HADIAH / GIFT ENTRIES ====================
    // The gift list is dynamic (bank accounts, e-wallets, shipping address), so
    // rows are driven from the DOM instead of fixed field names.
    function giftRows() {
        var list = document.getElementById('tsGiftList');
        return list ? Array.prototype.slice.call(list.querySelectorAll('[data-gift-row]')) : [];
    }

    function paintGiftNumbers() {
        giftRows().forEach(function(row, index) {
            var label = row.querySelector('[data-gift-number]');
            if (label) label.textContent = index + 1;
        });

        var counter = document.getElementById('tsGiftCount');
        if (counter) counter.textContent = giftRows().length;
    }

    function toggleGiftEmptyState(show) {
        var empty = document.getElementById('tsGiftEmpty');
        if (empty) empty.classList.toggle('d-none', !show);
    }

    // Bank / e-wallet entries show a number and a logo, an address entry shows
    // the shipping address instead.
    window.syncGiftRowFields = function(select) {
        var row = select && select.closest ? select.closest('[data-gift-row]') : null;
        if (!row) return;

        var isAddress = select.value === 'address';

        row.querySelectorAll('[data-gift-field]').forEach(function(field) {
            var name = field.getAttribute('data-gift-field');
            var visible = isAddress ? name === 'address' : (name === 'number' || name === 'logo');
            field.classList.toggle('d-none', !visible);
        });
    };

    window.addGiftRow = function() {
        var list = document.getElementById('tsGiftList');
        var tpl = document.getElementById('tsGiftRowTemplate');
        if (!list || !tpl) return;

        toggleGiftEmptyState(false);

        var fragment = tpl.content.cloneNode(true);
        var row = fragment.querySelector('[data-gift-row]');

        // A fresh id doubles as the storage key of this entry's logo slot.
        if (row) row.querySelector('[data-field="id"]').value = 'gift-' + Date.now();

        list.appendChild(fragment);
        paintGiftNumbers();

        if (!row) return;

        window.syncGiftRowFields(row.querySelector('[data-field="type"]'));

        var label = row.querySelector('[data-field="label"]');
        if (label) label.focus();
        if (row.scrollIntoView) row.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    };

    window.removeGiftRow = function(btn) {
        var row = btn.closest ? btn.closest('[data-gift-row]') : null;
        if (!row) return;

        var deleteBtn = row.querySelector('[data-gift-logo-delete]');

        if (deleteBtn && !deleteBtn.classList.contains('d-none')) {
            if (!window.confirm('Entri ini punya logo yang sudah diupload. Hapus entri beserta logonya?')) return;
        }

        row.remove();
        paintGiftNumbers();
        toggleGiftEmptyState(giftRows().length === 0);
    };

    window.moveGiftRow = function(btn, direction) {
        var row = btn.closest ? btn.closest('[data-gift-row]') : null;
        if (!row) return;

        var sibling = direction < 0 ? row.previousElementSibling : row.nextElementSibling;

        // Skip anything that is not a gift row (e.g. the empty-state message).
        while (sibling && !sibling.matches('[data-gift-row]')) {
            sibling = direction < 0 ? sibling.previousElementSibling : sibling.nextElementSibling;
        }

        if (!sibling) return;

        if (direction < 0) {
            sibling.parentNode.insertBefore(row, sibling);
        } else {
            sibling.parentNode.insertBefore(sibling, row);
        }

        paintGiftNumbers();
    };

    // Logos are uploaded straight into this entry's asset slot, without
    // refreshing the pane — otherwise unsaved edits in the other rows would be
    // thrown away.
    window.uploadGiftLogo = async function(input) {
        var row = input.closest ? input.closest('[data-gift-row]') : null;
        if (!row) return;

        var file = input.files[0];
        input.value = '';
        if (!file) return;

        if (!isSupportedImage(file)) {
            showToast('Format "' + (file.type || 'tidak dikenal') + '" tidak didukung.\nGunakan JPG, PNG, WebP, atau GIF.', 'error');
            return;
        }

        var id = row.querySelector('[data-field="id"]').value;

        if (!id) {
            showToast('Simpan entri hadiah ini dulu sebelum upload logo', 'error');
            return;
        }

        try {
            var prepared = await compressForUpload(file, 800, 0.9);
            var fd = new FormData();
            fd.append('asset_key', 'gift_logo_' + id);
            fd.append('file', prepared);

            var r = await fetch('{{ route("admin.template-settings.upload-asset") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: fd
            });

            var result = await readJsonResponse(r);

            if (result.data && result.data.success) {
                var preview = row.querySelector('[data-gift-logo-preview]');
                if (preview) {
                    preview.src = result.data.url;
                    preview.classList.remove('d-none');
                }

                var removeBtn = row.querySelector('[data-gift-logo-delete]');
                if (removeBtn) removeBtn.classList.remove('d-none');

                showToast('Logo hadiah berhasil diupload');
            } else {
                showToast(uploadHint(result), 'error');
            }
        } catch (e) {
            showToast('Upload gagal: ' + (e && e.message ? e.message : 'kesalahan tidak diketahui'), 'error');
        }
    };

    window.deleteGiftLogo = async function(btn) {
        var row = btn.closest ? btn.closest('[data-gift-row]') : null;
        if (!row) return;
        if (!window.confirm('Hapus logo hadiah ini?')) return;

        var id = row.querySelector('[data-field="id"]').value;

        try {
            var r = await fetch('{{ route("admin.template-settings.delete-asset") }}', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ asset_key: 'gift_logo_' + id })
            });
            var j = await r.json();

            if (!j.success) {
                showToast(j.message || 'Gagal hapus logo', 'error');
                return;
            }

            var preview = row.querySelector('[data-gift-logo-preview]');
            var fallback = row.getAttribute('data-default-logo-url') || '';

            if (preview) {
                if (fallback) {
                    preview.src = fallback;
                    preview.classList.remove('d-none');
                } else {
                    preview.removeAttribute('src');
                    preview.classList.add('d-none');
                }
            }

            btn.classList.add('d-none');
            showToast('Logo hadiah dihapus');
        } catch (e) {
            showToast('Error menghapus logo', 'error');
        }
    };

    // Names are (re)assigned from the DOM order right before submit, so an
    // up/down move or a removed row can never leave a gap or a duplicate index.
    function reindexGiftRows(form) {
        form.querySelectorAll('[data-gift-row] [data-field]').forEach(function(field) {
            field.removeAttribute('name');
        });

        giftRows().forEach(function(row, index) {
            row.querySelectorAll('[data-field]').forEach(function(field) {
                field.name = 'gifts[' + index + '][' + field.getAttribute('data-field') + ']';
            });
        });
    }

    window.saveGifts = function() {
        var form = document.getElementById('tsGiftForm');
        if (!form) return;

        reindexGiftRows(form);

        var fd = new FormData(form);
        fd.append('_method', 'PUT');

        fetch('{{ route("admin.template-settings.gifts.update") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: fd
        }).then(function(r) {
            return r.json().catch(function() {
                return { success: false, message: 'Respons server tidak valid (HTTP ' + r.status + ')' };
            });
        }).then(function(d) {
            if (d.success) {
                showToast(d.message || 'Data hadiah disimpan');
                refreshSettingsPane('#ts-gift');
            } else {
                var err = d.message || 'Gagal menyimpan';
                if (d.errors) err += '\n' + Object.values(d.errors).flat().join('\n');
                showToast(err, 'error');
            }
        }).catch(function() { showToast('Terjadi kesalahan', 'error'); });
    };

    function initSettingsGifts() {
        giftRows().forEach(function(row) {
            window.syncGiftRowFields(row.querySelector('[data-field="type"]'));
        });

        paintGiftNumbers();
    }
    window.initSettingsGifts = initSettingsGifts;

    window.saveSettingsForm = function(formId) {
        var form = document.getElementById(formId);
        if (!form) return;
        var formData = new FormData(form);
        formData.append('_method', 'PUT');
        fetch('{{ route("admin.template-settings.update") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: formData
        }).then(function(r) { return r.json(); }).then(function(d) {
            if (d.success) {
                showToast('Berhasil disimpan!');
            } else {
                var err = d.message || 'Error';
                if (d.errors) err += '\n' + Object.values(d.errors).flat().join('\n');
                showToast(err, 'error');
            }
        }).catch(function() { showToast('Terjadi kesalahan', 'error'); });
    };

    window.addStoryItem = function() {
        var container = document.getElementById('storyItemsContainer');
        if (!container) return;
        var count = container.querySelectorAll('.story-item').length;
        var div = document.createElement('div');
        div.className = 'story-item card mb-2';
        div.dataset.index = count;
        div.innerHTML = '<div class="card-body p-3">' +
            '<div class="d-flex justify-content-between align-items-center mb-2">' +
            '<strong style="font-size:0.85rem;">Cerita ' + (count + 1) + '</strong>' +
            '<button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest(\'.story-item\').remove()"><i class="fas fa-trash"></i></button>' +
            '</div>' +
            '<input type="text" class="form-control form-control-sm mb-2 story-item-title" placeholder="Judul (misal: PERTEMUAN (2017))">' +
            '<textarea class="form-control form-control-sm story-item-desc" rows="3" placeholder="Cerita..."></textarea>' +
            '</div>';
        container.appendChild(div);
    };

    window.saveStoryItems = function() {
        var container = document.getElementById('storyItemsContainer');
        if (!container) return;
        var items = [];
        container.querySelectorAll('.story-item').forEach(function(el) {
            var title = el.querySelector('.story-item-title').value.trim();
            var desc = el.querySelector('.story-item-desc').value.trim();
            if (title || desc) items.push({ title: title, description: desc });
        });
        var formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('template_settings[story_title]', document.getElementById('storyTitle').value);
        formData.append('template_settings[story_subtitle]', document.getElementById('storySubtitle').value);
        formData.append('template_settings[story_items]', JSON.stringify(items));
        fetch('{{ route("admin.template-settings.update") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: formData
        }).then(function(r) { return r.json(); }).then(function(d) {
            showToast(d.success ? 'Our Story tersimpan!' : (d.message || 'Error'), d.success ? 'success' : 'error');
        }).catch(function() { showToast('Terjadi kesalahan', 'error'); });
    };

    window.saveVideoUrl = function() {
        var url = document.getElementById('galleryVideoUrl').value.trim();
        var formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('template_settings[gallery_video_url]', url);
        fetch('{{ route("admin.template-settings.update") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: formData
        }).then(function(r) { return r.json(); }).then(function(d) {
            showToast(d.success ? 'Video tersimpan!' : (d.message || 'Error'), d.success ? 'success' : 'error');
        }).catch(function() { showToast('Terjadi kesalahan', 'error'); });
    };

    $(document).ready(function() {
        // Global variables
        let templates = [];
        let currentTemplate = null;
        let isRefreshing = false;
        let lastUpdateTime = new Date();
        let startY = 0;
        let currentY = 0;
        let pullDelta = 0;
        const pullToRefresh = $('#pullToRefresh');
        const pullThreshold = 60;

        // ==================== UTILITY FUNCTIONS ====================
        function showLoading() {
            $('#loadingOverlay').css('display', 'flex').hide().fadeIn();
        }
        function hideLoading() {
            $('#loadingOverlay').fadeOut();
        }

        // showToast() is defined once at the top level so both the dashboard and
        // the embedded template-settings handlers can use it (see above).

        // ==================== GUEST MANAGEMENT ====================
        // Add guest form
        $('#addGuestForm').on('submit', function(e) {
            e.preventDefault();
            showLoading();

            $.ajax({
                url: '{{ route("admin.guests.store") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        if (response.invitation_url) {
                            showAddGuestSuccess(response.invitation_url);
                        } else {
                            showSuccessMessage('Tamu berhasil ditambahkan!');
                        }
                        $('#addGuestForm')[0].reset();
                        refreshGuestsData();
                        refreshDashboardData();
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors && errors.name) {
                            showToast(errors.name[0], 'error');
                        } else {
                            showToast(xhr.responseJSON.message || 'Terjadi kesalahan validasi', 'error');
                        }
                    } else {
                        showToast('Error: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan'), 'error');
                    }
                }
            });
        });

        // Copy link functionality
        $(document).on('click', '.copy-link', function() {
            const url = $(this).data('url');
            navigator.clipboard.writeText(url).then(function() {
                showToast('Link berhasil disalin!', 'success');
            }).catch(function() {
                showToast('Gagal menyalin link', 'error');
            });
        });

        // WhatsApp chat functionality
        $(document).on('click', '.whatsapp-chat', function() {
            const phoneNumber = $(this).data('number');
            if (phoneNumber) {
                const whatsappUrl = `https://wa.me/${phoneNumber}`;
                window.open(whatsappUrl, '_blank');
            } else {
                showToast('Nomor WhatsApp tidak valid', 'error');
            }
        });

        // Delete guest
        $(document).on('click', '.delete-guest', function() {
            const guestId = this.getAttribute('data-id');
            const guestName = this.getAttribute('data-name');

            showElegantConfirm(
                `Anda akan menghapus tamu "<strong>${guestName}</strong>". Tindakan ini tidak dapat dibatalkan.`,
                {
                    title: 'Hapus Tamu',
                    type: 'delete',
                    confirmText: 'Ya, Hapus',
                    icon: 'trash'
                }
            ).then((confirmed) => {
                if (confirmed) {
                    showLoading();
                    $.ajax({
                        url: `{{ url("/admin/guests") }}/${guestId}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            hideLoading();
                            if (response.success) {
                                showSuccessMessage('Tamu berhasil dihapus!');
                                $(`[data-guest-id="${guestId}"]`).remove();
                                refreshDashboardData();
                                updateGuestCounts();
                            }
                        },
                        error: function(xhr) {
                            hideLoading();
                            showToast('Error menghapus tamu', 'error');
                        }
                    });
                }
            });
        });

        // Edit guest functionality
        $(document).on('click', '.edit-guest', function() {
            const guestId = this.getAttribute('data-id');
            const guestName = this.getAttribute('data-name');
            const guestAttends = this.getAttribute('data-guest-attends');
            const eventType = this.getAttribute('data-event-type');
            const whatsappNumber = this.getAttribute('data-whatsapp') || '';
            let attendance = this.getAttribute('data-attendance');

            // `event_type` now carries the group slug directly.
            const formattedEventType = eventType || defaultGroupSlug;

            if (!attendance || attendance === 'null' || attendance === 'undefined') {
                attendance = 'Belum Konfirmasi';
            }

            $('#editGuestId').val(guestId);
            $('#editGuestName').val(guestName);
            $('#editGuestAttends').val(guestAttends);
            $('#editWhatsappNumber').val(whatsappNumber);
            $('#editEventType').val(formattedEventType);
            $('#editAttendance').val(attendance);
            $('#editGuestModal').modal('show');
        });

        // Save edited guest
        $('#saveEditGuest').on('click', function() {
            const guestId = $('#editGuestId').val();
            const formData = {
                _token: '{{ csrf_token() }}',
                name: $('#editGuestName').val().trim(),
                guest_attends: $('#editGuestAttends').val(),
                whatsapp_number: $('#editWhatsappNumber').val().trim(),
                event_type: $('#editEventType').val(),
                attendance: $('#editAttendance').val() || 'Belum Konfirmasi'
            };

            if (!validateEditForm()) {
                return;
            }

            showLoading();

            $.ajax({
                url: `{{ url("/admin/guests") }}/${guestId}`,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        showToast('Data tamu berhasil diperbarui!', 'success');
                        $('#editGuestModal').modal('hide');
                        refreshGuestsData();
                        refreshDashboardData();
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors && errors.name) {
                            showToast(errors.name[0], 'error');
                        } else if (errors && errors.event_type) {
                            showToast(errors.event_type[0], 'error');
                        } else if (errors && errors.attendance) {
                            showToast(errors.attendance[0], 'error');
                        } else {
                            showToast(xhr.responseJSON.message || 'Terjadi kesalahan validasi', 'error');
                        }
                    } else {
                        showToast('Error: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan'), 'error');
                    }
                }
            });
        });

        function validateEditForm() {
            const name = $('#editGuestName').val().trim();
            const eventType = $('#editEventType').val();

            if (!name) {
                showToast('Nama tamu harus diisi', 'error');
                $('#editGuestName').focus();
                return false;
            }

            if (!eventType) {
                showToast('Jenis acara harus dipilih', 'error');
                $('#editEventType').focus();
                return false;
            }

            return true;
        }

        function showAddGuestSuccess(invitationUrl) {
            showElegantConfirm(
                'Tamu berhasil ditambahkan! Apakah Anda ingin menyalin link undangan ke clipboard?',
                {
                    title: 'Berhasil',
                    type: 'success',
                    confirmText: 'Ya, Salin Link',
                    cancelText: 'Nanti Saja',
                    icon: 'copy'
                }
            ).then((confirmed) => {
                if (confirmed) {
                    navigator.clipboard.writeText(invitationUrl).then(function() {
                        showSuccessMessage('Link berhasil disalin!');
                    }).catch(function() {
                        showToast('Gagal menyalin link', 'error');
                    });
                }
            });
        }

        // ==================== MESSAGE MANAGEMENT ====================
        // Delete message
        $(document).on('click', '.delete-message', function() {
            const messageId = this.getAttribute('data-id');
            const messageName = this.getAttribute('data-name') || 'Anonymous';

            showElegantConfirm(
                `Anda akan menghapus ucapan dari "<strong>${messageName}</strong>".`,
                {
                    title: 'Hapus Ucapan',
                    type: 'delete',
                    confirmText: 'Ya, Hapus',
                    icon: 'trash'
                }
            ).then((confirmed) => {
                if (confirmed) {
                    showLoading();
                    $.ajax({
                        url: `{{ url("/admin/messages") }}/${messageId}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            hideLoading();
                            if (response.success) {
                                showSuccessMessage('Ucapan berhasil dihapus!');
                                $(`[data-message-id="${messageId}"]`).remove();
                                refreshDashboardData();
                            }
                        },
                        error: function(xhr) {
                            hideLoading();
                            showToast('Error menghapus ucapan', 'error');
                        }
                    });
                }
            });
        });

        // ==================== TEMPLATE MANAGEMENT ====================
        // Function to open manage templates modal
        $("#manageTemplate").on("click", function() {
            loadAllTemplates();
            $('#manageTemplatesModal').modal('show');
        });

        // Load all templates for management
        function loadAllTemplates() {
            showLoading();
            $.ajax({
                url: '{{ route("admin.templates.index") }}',
                type: 'GET',
                success: function(response) {
                    hideLoading();
                    templates = response;
                    populateTemplatesTable(templates);
                },
                error: function(xhr) {
                    hideLoading();
                    showToast('Gagal memuat template', 'error');
                    console.error('Error loading templates:', xhr);
                }
            });
        }

        // Populate templates table
        function populateTemplatesTable(templates) {
            const $tbody = $('#templatesTableBody');
            $tbody.empty();

            if (templates.length === 0) {
                $tbody.append(`
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            Belum ada template
                        </td>
                    </tr>
                `);
                return;
            }

            templates.forEach(template => {
                const templateJson = JSON.stringify(template).replace(/'/g, "&#39;").replace(/"/g, "&quot;");

                const $row = $(`
                    <tr data-template-id="${template.id}">
                        <td>
                            <strong>${template.name}</strong>
                            ${template.is_default ? '<span class="badge bg-success ms-2">Default</span>' : ''}
                        </td>
                        <td>
                            <span class="badge ${template.is_active ? 'bg-success' : 'bg-secondary'}">
                                ${template.is_active ? 'Aktif' : 'Non-Aktif'}
                            </span>
                        </td>
                        <td>${template.is_default ? '<i class="fas fa-star text-warning"></i> Ya' : 'Tidak'}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary edit-template"
                                        data-template='${templateJson}'
                                        title="Edit Template">
                                    <i class="fas fa-edit"></i>
                                </button>
                                ${!template.is_default ? `
                                <button class="btn btn-outline-success set-default-template"
                                        data-id="${template.id}"
                                        title="Jadikan Default">
                                    <i class="fas fa-star"></i>
                                </button>
                                <button class="btn btn-outline-danger delete-template"
                                        data-id="${template.id}"
                                        data-name="${template.name}"
                                        title="Hapus Template">
                                    <i class="fas fa-trash"></i>
                                </button>
                                ` : `
                                <button class="btn btn-outline-secondary" disabled title="Template Default Tidak Dapat Dihapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                `}
                            </div>
                        </td>
                    </tr>
                `);
                $tbody.append($row);
            });
        }

        // Load active templates for share modal
        function loadTemplates() {
            $.ajax({
                url: '{{ route("admin.templates.active") }}',
                type: 'GET',
                success: function(response) {
                    templates = response;
                    populateTemplateSelect();

                    const defaultTemplate = templates.find(t => t.is_default) || templates[0];
                    if (defaultTemplate) {
                        $('#templateSelect').val(defaultTemplate.id);
                        currentTemplate = defaultTemplate;
                        updateMessagePreview();
                    }
                },
                error: function(xhr) {
                    console.error('Error loading active templates:', xhr);
                    showToast('Gagal memuat template', 'error');
                }
            });
        }

        // Populate template select dropdown
        function populateTemplateSelect() {
            const $select = $('#templateSelect');
            $select.empty();

            if (templates.length === 0) {
                $select.append('<option value="">Tidak ada template</option>');
                return;
            }

            templates.forEach(template => {
                $select.append(
                    $('<option>', {
                        value: template.id,
                        text: template.name + (template.is_default ? ' (Default)' : '')
                    })
                );
            });
        }

        // Add/Edit template form
        $('#addTemplateForm').on('submit', function(e) {
            e.preventDefault();

            const isEditMode = $(this).data('edit-mode');
            const editId = $(this).data('edit-id');

            const formData = {
                _token: '{{ csrf_token() }}',
                name: $(this).find('input[name="name"]').val(),
                template: $(this).find('textarea[name="template"]').val(),
                is_active: $(this).find('#isActive').is(':checked') ? 1 : 0,
                is_default: $(this).find('#isDefault').is(':checked') ? 1 : 0
            };

            if (!formData.name.trim()) {
                showToast('Nama template harus diisi', 'error');
                return;
            }

            if (!formData.template.trim()) {
                showToast('Template pesan harus diisi', 'error');
                return;
            }

            showLoading();

            $.ajax({
                url: isEditMode ? `{{ url('/admin/templates') }}/${editId}` : '{{ route("admin.templates.store") }}',
                type: isEditMode ? 'PUT' : 'POST',
                data: formData,
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        showToast(isEditMode ? 'Template berhasil diperbarui' : 'Template berhasil ditambahkan', 'success');
                        resetTemplateForm();
                        loadAllTemplates();
                        loadTemplates();
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors.name) {
                            showToast(errors.name[0], 'error');
                        } else if (errors.template) {
                            showToast(errors.template[0], 'error');
                        } else {
                            showToast('Terjadi kesalahan validasi', 'error');
                        }
                    } else {
                        showToast('Error menyimpan template', 'error');
                    }
                    console.error('Error saving template:', xhr);
                }
            });
        });

        // Edit template functionality
        $(document).on('click', '.edit-template', function() {
            const templateJson = $(this).data('template');
            const template = typeof templateJson === 'string' ?
                JSON.parse(templateJson.replace(/&#39;/g, "'").replace(/&quot;/g, '"')) : templateJson;

            $('#addTemplateForm input[name="name"]').val(template.name);
            $('#addTemplateForm textarea[name="template"]').val(template.template);
            $('#addTemplateForm #isActive').prop('checked', template.is_active);
            $('#addTemplateForm #isDefault').prop('checked', template.is_default);

            $('#addTemplateForm').data('edit-mode', true);
            $('#addTemplateForm').data('edit-id', template.id);
            $('#addTemplateForm button[type="submit"]').html('<i class="fas fa-save me-1"></i> Update Template');

            $('html, body').animate({
                scrollTop: $('#addTemplateForm').offset().top - 100
            }, 500);
        });

        // Set default template
        $(document).on('click', '.set-default-template', function() {
            const templateId = $(this).data('id');
            const templateName = $(this).closest('tr').find('strong').text().trim();

            showElegantConfirm(
                `Jadikan template "<strong>${templateName}</strong>" sebagai template default?`,
                {
                    title: 'Template Default',
                    type: 'info',
                    confirmText: 'Ya, Jadikan Default',
                    icon: 'star'
                }
            ).then((confirmed) => {
                if (confirmed) {
                    showLoading();
                    $.ajax({
                        url: `{{ url("/admin/templates") }}/${templateId}/set-default`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            hideLoading();
                            if (response.success) {
                                showSuccessMessage('Template default berhasil diubah!');
                                loadAllTemplates();
                                loadTemplates();
                            }
                        },
                        error: function(xhr) {
                            hideLoading();
                            showToast('Error mengubah template default', 'error');
                        }
                    });
                }
            });
        });

        // Delete template
        $(document).on('click', '.delete-template', function() {
            const templateId = $(this).data('id');
            const templateName = $(this).data('name');

            showElegantConfirm(
                `Anda akan menghapus template "<strong>${templateName}</strong>". Tindakan ini tidak dapat dibatalkan.`,
                {
                    title: 'Hapus Template',
                    type: 'delete',
                    confirmText: 'Ya, Hapus',
                    icon: 'trash'
                }
            ).then((confirmed) => {
                if (confirmed) {
                    showLoading();
                    $.ajax({
                        url: `{{ url("/admin/templates") }}/${templateId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            hideLoading();
                            if (response.success) {
                                showSuccessMessage('Template berhasil dihapus!');
                                loadAllTemplates();
                                loadTemplates();
                            }
                        },
                        error: function(xhr) {
                            hideLoading();
                            if (xhr.status === 422) {
                                showElegantConfirm(
                                    xhr.responseJSON.message || 'Tidak dapat menghapus template default.',
                                    {
                                        title: 'Tidak Dapat Dihapus',
                                        type: 'warning',
                                        confirmText: 'Mengerti',
                                        icon: 'info-circle'
                                    }
                                );
                            } else {
                                showToast('Error menghapus template', 'error');
                            }
                        }
                    });
                }
            });
        });

        // Reset form ketika modal ditutup
        $('#manageTemplatesModal').on('hidden.bs.modal', function() {
            resetTemplateForm();
        });

        // Function to reset template form
        function resetTemplateForm() {
            $('#addTemplateForm')[0].reset();
            $('#addTemplateForm').removeData('edit-mode');
            $('#addTemplateForm').removeData('edit-id');
            $('#addTemplateForm button[type="submit"]').html('<i class="fas fa-save me-1"></i> Simpan Template');
            $('#isActive').prop('checked', true);
            $('#isDefault').prop('checked', false);
        }

        // Update template selection in share modal
        $('#templateSelect').on('change', function() {
            const templateId = $(this).val();
            currentTemplate = templates.find(t => t.id == templateId);
            if (currentTemplate) {
                updateMessagePreview();
            }
        });

        // Update message preview
        function updateMessagePreview() {
            if (!currentTemplate) return;

            const guestName = $('#modalGuestName').val();
            const invitationLink = $('#modalInvitationLink').val();
            const eventData = $('#shareModal').data('event-data');

            if (!eventData) return;

            let message = currentTemplate.template;

            const groomName = '{{ $activeTemplate->getSetting("groom_name", "Mempelai Pria") }}';
            const brideName = '{{ $activeTemplate->getSetting("bride_name", "Mempelai Wanita") }}';

            message = message.replace(/{guest_name}/g, guestName || 'Nama Tamu');
            message = message.replace(/{groom_name}/g, groomName);
            message = message.replace(/{bride_name}/g, brideName);
            message = message.replace(/{event_date}/g, eventData.formattedDate || 'Tanggal Acara');
            message = message.replace(/{event_time}/g, eventData.eventTime || 'Waktu Acara');
            message = message.replace(/{event_location}/g, eventData.eventLocation || 'Lokasi Acara');
            message = message.replace(/{invitation_link}/g, invitationLink || 'Link Undangan');

            $('#modalMessageTemplate').val(message);
        }

        // Update share guest via WhatsApp dengan template
        $(document).on('click', '.share-guest-whatsapp', function() {
            const guestName = $(this).data('name');
            const event = $(this).data('event');
            const phoneNumber = $(this).data('number');

            if (!event || typeof event !== 'object') {
                showToast('Data event tidak tersedia untuk tamu ini', 'error');
                return;
            }

            const baseUrl = '{{ url("/") }}';
            const groupSlug = event.event_key || defaultGroupSlug;
            const invitationLink = `${baseUrl}/${groupSlug}/invitation?to=${encodeURIComponent(guestName)}`;

            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            };

            const formatter = new Intl.DateTimeFormat('id-ID', options);
            const formattedDate = event.event_date ? formatter.format(new Date(event.event_date)) : 'Tanggal belum ditentukan';
            const eventTime = event.start_time && event.finish_time ? `${event.start_time} WITA - ${event.finish_time}` : 'Waktu belum ditentukan';
            const eventLocation = event.location || 'Lokasi belum ditentukan';

            $('#shareModal').data('event-data', {
                formattedDate,
                eventTime,
                eventLocation
            });

            $('#modalGuestName').val(guestName);
            $('#modalInvitationLink').val(invitationLink);
            $('#modalWhatsappNumber').val(phoneNumber);

            $('#shareModal').modal('show');
        });

        // Update modal share WhatsApp button
        $('#modalShareWhatsApp').on('click', function() {
            if (!currentTemplate) {
                showToast('Pilih template terlebih dahulu', 'error');
                return;
            }

            const message = $('#modalMessageTemplate').val();
            const phoneNumber = $('#modalWhatsappNumber').val();
            if (!message.trim()) {
                showToast('Pesan tidak boleh kosong', 'error');
                return;
            }

            const encodedMessage = encodeURIComponent(message);
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
            window.open(whatsappUrl, '_blank');
            $('#shareModal').modal('hide');
        });

        // Modal copy link
        $('#modalCopyLink').on('click', function() {
            const invitationLink = $('#modalInvitationLink').val();
            navigator.clipboard.writeText(invitationLink).then(function() {
                showToast('Link berhasil disalin!', 'success');
            }).catch(function() {
                showToast('Gagal menyalin link', 'error');
            });
        });

        // Load templates when share modal is shown
        $('#shareModal').on('show.bs.modal', function() {
            loadTemplates();
        });

        // ==================== FILTER & SEARCH FUNCTIONALITY ====================
        // Filter functionality
        $('#eventFilter, #statusFilter').on('change', function() {
            applyFilters();
        });

        // Confirm untuk reset filter
        $('#resetFilter').on('click', function() {
            showElegantConfirm(
                'Anda akan mengatur ulang semua filter pencarian. Lanjutkan?',
                {
                    title: 'Reset Filter',
                    type: 'info',
                    confirmText: 'Ya, Reset',
                    icon: 'filter'
                }
            ).then((confirmed) => {
                if (confirmed) {
                    $('#eventFilter').val('all');
                    $('#statusFilter').val('all');
                    $('#searchFilter').val('');
                    applyFilters();
                    showSuccessMessage('Filter berhasil direset!');
                }
            });
        });

        // Search functionality
        $('#searchFilter').on('input', function() {
            applyFilters();

            const hasValue = $(this).val().length > 0;
            $(this).parent().find('.search-clear').remove();

            if (hasValue) {
                const clearBtn = $('<button type="button" class="btn btn-sm search-clear" style="border: none; background: transparent; position: absolute; right: 5px; top: 50%; transform: translateY(-50%); z-index: 3;">' +
                    '<i class="fas fa-times text-muted"></i>' +
                    '</button>');

                $(this).parent().css('position', 'relative').append(clearBtn);

                clearBtn.on('click', function() {
                    $('#searchFilter').val('');
                    applyFilters();
                    $(this).remove();
                });
            }
        });

        // Clear search with ESC key
        $('#searchFilter').on('keydown', function(e) {
            if (e.key === 'Escape') {
                $(this).val('');
                applyFilters();
            }
        });

        function applyFilters() {
            const eventFilter = $('#eventFilter').val();
            const statusFilter = $('#statusFilter').val();
            const searchTerm = $('#searchFilter').val().toLowerCase().trim();

            let visibleCount = 0;
            let totalCount = 0;

            function matchesFilters($el) {
                const eventType = $el.data('event-type');
                let attendance = $el.data('attendance');
                const nameSelector = $el.hasClass('card') ? '.card-title' : 'td:first strong';
                const guestName = $el.find(nameSelector).text().toLowerCase();

                if (!attendance || attendance === 'null' || attendance === 'undefined') {
                    attendance = 'Belum Konfirmasi';
                }

                if (searchTerm !== '' && !guestName.includes(searchTerm)) return false;

                if (eventFilter !== 'all' && eventType !== eventFilter) return false;

                if (statusFilter !== 'all') {
                    if (statusFilter === 'Belum Konfirmasi') {
                        if (attendance && attendance !== '' && attendance !== 'Belum Konfirmasi') return false;
                    } else if (attendance !== statusFilter) {
                        return false;
                    }
                }
                return true;
            }

            function toggleHighlight($el, show) {
                const nameSelector = $el.hasClass('card') ? '.card-title' : 'td:first strong';
                const $nameEl = $el.find(nameSelector);
                const originalText = $nameEl.data('original-text') || $nameEl.text();
                $nameEl.data('original-text', originalText);

                if (show && searchTerm !== '') {
                    const escaped = escapeRegex(searchTerm);
                    $nameEl.html(originalText.replace(new RegExp(escaped, 'gi'), m => '<span class="highlight">' + m + '</span>'));
                } else {
                    $nameEl.text(originalText);
                    $nameEl.removeData('original-text');
                }
            }

            $('#guestsTableBody tr, #mobileGuestsList .card').each(function() {
                const show = matchesFilters($(this));
                $(this).toggle(show);
                toggleHighlight($(this), show);
                if ($(this).is('tr')) totalCount++;
                if (show && $(this).is('tr')) visibleCount++;
            });

            $('#filteredCount').text(visibleCount);
            $('#totalCount').text(totalCount);

            updateFilterInfo(eventFilter, statusFilter, searchTerm);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }

        function escapeRegex(str) {
            return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }


        function updateFilterInfo(eventFilter, statusFilter, searchTerm = '') {
            let infoText = '';

            if (searchTerm !== '') {
                infoText += `Pencarian: "${searchTerm}"`;
            }

            if (eventFilter !== 'all') {
                if (infoText !== '') infoText += ' | ';
                infoText += `Grup: ${groupLabels[eventFilter] || eventFilter}`;
            }

            if (statusFilter !== 'all') {
                if (infoText !== '') infoText += ' | ';
                infoText += `Status: ${statusFilter}`;
            }

            if (infoText === '') {
                infoText = 'Semua tamu ditampilkan';
            }

            $('#filterInfo').text(infoText);
        }

        function updateGuestCounts() {
            const totalCount = $('#guestsTableBody tr').length;
            const visibleCount = $('#guestsTableBody tr:visible').length;

            $('#filteredCount').text(visibleCount);
            $('#totalCount').text(totalCount);
        }

        // ==================== REFRESH FUNCTIONALITY ====================
        // Manual refresh buttons
        $('#refreshGuests').on('click', function() {
            refreshGuestsData();
        });

        $('#refreshMessages').on('click', function() {
            refreshMessagesData();
        });

        // Pull to refresh functionality
        document.addEventListener('touchstart', (e) => {
            if (window.scrollY === 0 && $('#dashboard').hasClass('active')) {
                startY = e.touches[0].clientY;
                pullToRefresh.css('display', 'block');
            }
        }, { passive: true });

        document.addEventListener('touchmove', (e) => {
            if (!startY || !$('#dashboard').hasClass('active')) return;

            currentY = e.touches[0].clientY;
            pullDelta = currentY - startY;

            if (pullDelta > 0) {
                e.preventDefault();
                pullToRefresh.css('transform', `translateY(${pullDelta}px)`);

                if (pullDelta > pullThreshold) {
                    pullToRefresh.html('<i class="fas fa-sync-alt fa-spin me-2"></i> Lepaskan untuk memperbarui');
                } else {
                    pullToRefresh.html('<i class="fas fa-sync-alt me-2"></i> Tarik ke bawah untuk memperbarui');
                }
            }
        }, { passive: false });

        document.addEventListener('touchend', () => {
            if (pullDelta > pullThreshold && $('#dashboard').hasClass('active')) {
                refreshDashboardData();
            }

            pullToRefresh.css({
                'transform': 'translateY(0)',
                'transition': 'transform 0.3s'
            });

            setTimeout(() => {
                pullToRefresh.css('display', 'none');
                pullToRefresh.css('transition', '');
                pullToRefresh.html('<i class="fas fa-sync-alt me-2"></i> Tarik ke bawah untuk memperbarui');
            }, 300);

            startY = 0;
            currentY = 0;
            pullDelta = 0;
        });

        function refreshDashboardData() {
            if (isRefreshing) return;
            isRefreshing = true;

            $.ajax({
                url: '{{ route("admin.dashboard.data") }}',
                type: 'GET',
                success: function(response) {
                    $('#totalGuests').text(response.stats.total_guests);
                    $('#allGuestsCount').text(response.stats.all_guests_count);
                    $('#totalPeople').text(response.stats.total_people);
                    $('#totalMessages').text(response.stats.total_messages);
                    $('#pendingGuests').text(response.stats.pending_guests);

                    // Stats are rendered per group, so the cards are looked up by
                    // slug instead of the old hardcoded gedung/rumah pair.
                    const groupStats = response.eventStats || {};
                    Object.keys(groupStats).forEach(function(slug) {
                        const groupStat = groupStats[slug];
                        $('#event-' + slug + '-all').text(groupStat.all_guests_count);
                        $('#event-' + slug + '-attending').text(groupStat.total_guests);
                        $('#event-' + slug + '-people').text(groupStat.guest_attends_total);
                        $('#event-' + slug + '-messages').text(groupStat.total_messages);

                        const progress = groupStat.all_guests_count > 0
                            ? ((groupStat.attending_guests + groupStat.not_attending_guests) / groupStat.all_guests_count) * 100
                            : 0;
                        $('#event-' + slug + '-progress').css('width', `${progress}%`);
                    });

                    lastUpdateTime = new Date();
                    isRefreshing = false;
                },
                error: function() {
                    isRefreshing = false;
                    showToast('Gagal memperbarui data dashboard', 'error');
                }
            });
        }

        function refreshGuestsData() {
            if (isRefreshing) return;
            isRefreshing = true;
            showLoading();
            $.ajax({
                url: '{{ route("admin.guests.data") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    if (!response.success || !response.guests) {
                        isRefreshing = false;
                        showToast('Format data tidak valid', 'error');
                        return;
                    }
                    const guests = response.guests;
                    const baseUrl = '{{ url("/") }}';

                    function getAttendanceBadge(attendance) {
                        if (attendance === 'Hadir') return '<span class="badge bg-success badge-custom">Hadir</span>';
                        if (attendance === 'Tidak Hadir') return '<span class="badge bg-danger badge-custom">Tidak Hadir</span>';
                        return '<span class="badge bg-warning badge-custom">Belum Konfirmasi</span>';
                    }

                    function getEventBadge(event) {
                        if (!event) return '<span class="badge bg-secondary badge-custom">-</span>';
                        const slug = event.event_key || '';
                        return '<span class="badge ' + (groupColors[slug] || 'bg-primary') + ' badge-custom">' + escapeHtml(groupLabels[slug] || slug) + '</span>';
                    }

                    let tableHtml = '';
                    guests.forEach(function(g) {
                        const eventKey = g.event ? g.event.event_key : defaultGroupSlug;
                        const path = eventKey;
                        const inviteUrl = baseUrl + '/' + path + '/invitation?to=' + encodeURIComponent(g.name);
                        const eventData = g.event ? JSON.stringify(g.event).replace(/'/g, '&#39;').replace(/"/g, '&quot;') : 'null';
                        tableHtml += '<tr data-guest-id="' + g.id + '" data-event-type="' + eventKey + '" data-attendance="' + (g.attendance || 'Belum Konfirmasi') + '" data-whatsapp="' + (g.whatsapp_number || '') + '">';
                        tableHtml += '<td><strong>' + escapeHtml(g.name) + '</strong><br><small class="text-muted">' + escapeHtml(g.code) + '</small></td>';
                        tableHtml += '<td>' + getEventBadge(g.event) + '</td>';
                        tableHtml += '<td><small>' + (g.whatsapp_number || '<span class="text-muted">-</span>') + '</small></td>';
                        tableHtml += '<td>' + (g.guest_attends || 0) + ' orang</td>';
                        tableHtml += '<td>' + getAttendanceBadge(g.attendance) + '<br><small class="text-muted">' + (g.is_opened ? 'Dibuka' : 'Belum dibuka') + '</small></td>';
                        tableHtml += '<td><small class="text-muted">' + (g.created_at || '') + '</small></td>';
                        tableHtml += '<td><small class="text-muted">' + (g.updated_at || '') + '</small></td>';
                        tableHtml += '<td><div class="btn-group btn-group-sm">';
                        tableHtml += '<button class="btn btn-sm btn-whatsapp share-guest-whatsapp" data-name="' + escapeHtml(g.name) + '" data-event=\'' + eventData + '\' data-number="' + (g.formatted_whatsapp_number || '') + '" title="Share via WhatsApp"><i class="fab fa-whatsapp"></i></button>';
                        tableHtml += '<button class="btn btn-outline-primary copy-link" data-url="' + inviteUrl + '" title="Copy Link"><i class="fas fa-copy"></i></button>';
                        tableHtml += '<a href="' + inviteUrl + '" target="_blank" class="btn btn-outline-info" title="Preview"><i class="fas fa-eye"></i></a>';
                        tableHtml += '</div></td>';
                        tableHtml += '<td><div class="guest-share-actions">';
                        tableHtml += '<button class="btn btn-sm btn-edit edit-guest" data-id="' + g.id + '" data-name="' + escapeHtml(g.name) + '" data-guest-attends="' + (g.guest_attends || 1) + '" data-event-type="' + eventKey + '" data-attendance="' + (g.attendance || '') + '" data-whatsapp="' + (g.whatsapp_number || '') + '" title="Edit Tamu"><i class="fas fa-edit"></i></button>';
                        tableHtml += '<button class="btn btn-sm btn-outline-danger delete-guest" data-id="' + g.id + '" data-name="' + escapeHtml(g.name) + '" title="Hapus Tamu"><i class="fas fa-trash"></i></button>';
                        tableHtml += '</div></td></tr>';
                    });
                    $('#guestsTableBody').html(tableHtml);

                    let mobileHtml = '';
                    guests.forEach(function(g) {
                        const eventKey = g.event ? g.event.event_key : defaultGroupSlug;
                        const path = eventKey;
                        const inviteUrl = baseUrl + '/' + path + '/invitation?to=' + encodeURIComponent(g.name);
                        const eventData = g.event ? JSON.stringify(g.event).replace(/'/g, '&#39;').replace(/"/g, '&quot;') : 'null';
                        mobileHtml += '<div class="card mb-3" data-guest-id="' + g.id + '" data-event-type="' + eventKey + '" data-attendance="' + (g.attendance || 'Belum Konfirmasi') + '" data-whatsapp="' + (g.whatsapp_number || '') + '">';
                        mobileHtml += '<div class="card-body">';
                        mobileHtml += '<h6 class="card-title">' + escapeHtml(g.name) + '</h6>';
                        mobileHtml += '<p class="card-text mb-1"><small class="text-muted">Kode: ' + escapeHtml(g.code) + '</small></p>';
                        mobileHtml += '<p class="card-text mb-1"><strong>Acara:</strong> ' + getEventBadge(g.event) + '</p>';
                        mobileHtml += '<p class="card-text mb-1"><strong>WhatsApp:</strong> ' + (g.whatsapp_number || '-') + '</p>';
                        mobileHtml += '<p class="card-text mb-1"><strong>Jumlah:</strong> ' + (g.guest_attends || 0) + ' orang</p>';
                        mobileHtml += '<p class="card-text mb-1"><strong>Status:</strong> ' + getAttendanceBadge(g.attendance) + ' <small class="text-muted">(' + (g.is_opened ? 'Dibuka' : 'Belum dibuka') + ')</small></p>';
                        mobileHtml += '<div class="btn-group w-100 mt-2">';
                        mobileHtml += '<button class="btn btn-sm btn-whatsapp share-guest-whatsapp" data-name="' + escapeHtml(g.name) + '" data-event=\'' + eventData + '\' data-number="' + (g.formatted_whatsapp_number || '') + '"><i class="fab fa-whatsapp"></i> Share</button>';
                        mobileHtml += '<button class="btn btn-sm btn-outline-primary copy-link" data-url="' + inviteUrl + '"><i class="fas fa-copy"></i> Copy</button>';
                        mobileHtml += '<a href="' + inviteUrl + '" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i> View</a>';
                        mobileHtml += '</div>';
                        mobileHtml += '<div class="btn-group w-100 mt-2">';
                        mobileHtml += '<button class="btn btn-sm btn-outline-primary edit-guest" data-id="' + g.id + '" data-name="' + escapeHtml(g.name) + '" data-guest-attends="' + (g.guest_attends || 1) + '" data-event-type="' + eventKey + '" data-attendance="' + (g.attendance || '') + '" data-whatsapp="' + (g.whatsapp_number || '') + '"><i class="fas fa-edit"></i> Edit</button>';
                        mobileHtml += '<button class="btn btn-sm btn-outline-danger delete-guest" data-id="' + g.id + '" data-name="' + escapeHtml(g.name) + '"><i class="fas fa-trash"></i> Hapus</button>';
                        mobileHtml += '</div></div></div>';
                    });
                    $('#mobileGuestsList').html(mobileHtml);

                    lastUpdateTime = new Date();
                    isRefreshing = false;
                    showToast('Data tamu diperbarui', 'success');
                    updateGuestCounts();
                },
                error: function() {
                    hideLoading();
                    isRefreshing = false;
                    showToast('Gagal memperbarui data tamu', 'error');
                }
            });
        }

        function refreshMessagesData() {
            if (isRefreshing) return;
            isRefreshing = true;
            showLoading();
            $.ajax({
                url: '{{ route("admin.messages.data") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    if (!response.success || !response.messages) {
                        isRefreshing = false;
                        showToast('Format data tidak valid', 'error');
                        return;
                    }
                    const messages = response.messages;
                    let tableHtml = '';
                    messages.forEach(function(m) {
                        const name = m.guest ? escapeHtml(m.guest.name) : 'Anonymous';
                        const attendance = m.guest ? (m.guest.attendance || 'Belum Konfirmasi') : 'Belum Konfirmasi';
                        const attendanceBadge = attendance === 'Hadir' ? '<span class="badge bg-success">Hadir</span>' : (attendance === 'Tidak Hadir' ? '<span class="badge bg-danger">Tidak Hadir</span>' : '<span class="badge bg-warning">Belum Konfirmasi</span>');
                        tableHtml += '<tr>';
                        tableHtml += '<td><strong>' + name + '</strong></td>';
                        tableHtml += '<td>' + attendanceBadge + '</td>';
                        tableHtml += '<td><small>' + (m.message || '') + '</small></td>';
                        tableHtml += '<td><small class="text-muted">' + (m.created_at || '') + '</small></td>';
                        tableHtml += '<td><button class="btn btn-sm btn-outline-danger delete-message" data-id="' + m.id + '" title="Hapus"><i class="fas fa-trash"></i></button></td>';
                        tableHtml += '</tr>';
                    });
                    if (messages.length === 0) {
                        tableHtml = '<tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2"></i><br>Belum ada ucapan</td></tr>';
                    }
                    $('#messagesTableBody').html(tableHtml);
                    lastUpdateTime = new Date();
                    isRefreshing = false;
                    showToast('Data ucapan diperbarui', 'success');
                },
                error: function() {
                    hideLoading();
                    isRefreshing = false;
                    showToast('Gagal memperbarui data ucapan', 'error');
                }
            });
        }

        // ==================== VALIDATION & INITIALIZATION ====================
        // Real-time validation untuk nama tamu
        $('#guestNameInput').on('blur', function() {
            const name = $(this).val();
            const eventType = $('#eventTypeSelect').val();

            if (name.length > 0) {
                checkGuestExists(name, eventType);
            }
        });

        $('#eventTypeSelect').on('change', function() {
            const name = $('#guestNameInput').val();

            if (name.length > 0) {
                checkGuestExists(name, $(this).val());
            }
        });

        function checkGuestExists(name, eventType) {
            $.ajax({
                url: '{{ route("admin.guests.check") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    event_type: eventType
                },
                success: function(response) {
                    if (response.exists) {
                        $('#nameError').removeClass('d-none').text(response.message);
                        $('#submitGuestBtn').prop('disabled', true);
                        $('#guestNameInput').addClass('is-invalid');
                    } else {
                        $('#nameError').addClass('d-none');
                        $('#submitGuestBtn').prop('disabled', false);
                        $('#guestNameInput').removeClass('is-invalid');
                    }
                }
            });
        }

        // Export filtered data
        $('#exportFiltered').on('click', function() {
            const eventFilter = $('#eventFilter').val();
            const statusFilter = $('#statusFilter').val();

            let filterInfo = '';
            if (eventFilter !== 'all') {
                filterInfo += `Grup: ${groupLabels[eventFilter] || eventFilter}`;
            }
            if (statusFilter !== 'all') {
                if (filterInfo) filterInfo += ', ';
                filterInfo += `Status: ${statusFilter === 'Belum Konfirmasi' ? 'Belum Konfirmasi' : statusFilter}`;
            }

            const message = filterInfo ?
                `Export data dengan filter: <strong>${filterInfo}</strong>?` :
                'Export semua data tamu?';

            showElegantConfirm(
                message,
                {
                    title: 'Export Data',
                    type: 'info',
                    confirmText: 'Ya, Export',
                    icon: 'download'
                }
            ).then((confirmed) => {
                if (confirmed) {
                    let url = '{{ route("admin.guests.export.filtered") }}';
                    url += `?event=${eventFilter}&status=${statusFilter}`;
                    window.location.href = url;
                    showSuccessMessage('Data sedang diexport...');
                }
            });
        });

        // Initialize on page load
        function initializePage() {
            updateFilterInfo('all', 'all');
            updateGuestCounts();
            console.log('Admin dashboard initialized successfully');
        }

        // ==================== ELEGANT CONFIRM SYSTEM ====================
        function showElegantConfirm(message, options = {}) {
            return new Promise((resolve) => {
                const {
                    title = 'Konfirmasi',
                    type = 'warning',
                    confirmText = 'Ya, Lanjutkan',
                    cancelText = 'Batal',
                    icon = 'exclamation-circle'
                } = options;

                $('#elegantConfirmModalLabel').text(title);
                $('#confirmMessage').html(message);
                $('#confirmActionBtn').html(`<i class="fas fa-${icon} me-2"></i>${confirmText}`);
                $('#elegantConfirmModal .btn-outline-secondary').html(`<i class="fas fa-times me-2"></i>${cancelText}`);

                const $confirmIcon = $('#elegantConfirmModal .confirm-icon');
                $confirmIcon.removeClass('warning delete info success').addClass(type);
                $confirmIcon.find('i').removeClass().addClass(`fas fa-${icon} text-${getColorByType(type)}`);

                const $confirmBtn = $('#confirmActionBtn');
                $confirmBtn.removeClass('btn-primary btn-danger btn-warning btn-info btn-success')
                    .addClass(`btn-${getButtonClassByType(type)}`);

                if (type === 'warning' || type === 'info') {
                    $confirmBtn.css({
                        'background': `linear-gradient(135deg, var(--${type === 'warning' ? 'warning' : 'info'}), ${getLightColor(type)})`,
                        'border': 'none',
                        'box-shadow': `0 4px 15px rgba(var(--${type}-rgb), 0.3)`
                    });
                } else {
                    $confirmBtn.css({
                        'background': '',
                        'border': '',
                        'box-shadow': ''
                    });
                }

                const confirmModal = new bootstrap.Modal(document.getElementById('elegantConfirmModal'));
                confirmModal.show();

                $('#confirmActionBtn').off('click').on('click', function() {
                    $('#elegantConfirmModal').off('hidden.bs.modal');
                    confirmModal.hide();
                    resolve(true);
                });

                $('#elegantConfirmModal').off('hidden.bs.modal').on('hidden.bs.modal', function() {
                    resolve(false);
                });
            });
        }

        function getColorByType(type) {
            const colors = {
                'warning': 'warning',
                'delete': 'danger',
                'info': 'info',
                'success': 'success'
            };
            return colors[type] || 'warning';
        }

        function getButtonClassByType(type) {
            const buttons = {
                'warning': 'warning',
                'delete': 'danger',
                'info': 'info',
                'success': 'success'
            };
            return buttons[type] || 'primary';
        }

        function getLightColor(type) {
            const colors = {
                'warning': '#ffda6a',
                'info': '#6edff6',
                'success': '#75b798',
                'delete': '#e6858f'
            };
            return colors[type] || '#ffda6a';
        }

        function showSuccessMessage(message, duration = 2000) {
            $('#successMessage').text(message);
            const successModal = new bootstrap.Modal(document.getElementById('successToastModal'));
            successModal.show();

            setTimeout(() => {
                successModal.hide();
            }, duration);
        }

        // Run initialization
        initializePage();

        // ==================== WEDDING TEMPLATE MANAGEMENT ====================
        // Activate wedding template
        $(document).on('click', '.activate-wedding-template', function() {
            const btn = $(this);
            const templateId = btn.data('id');
            const originalHtml = btn.html();

            // Show loading state on button
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Mengaktifkan...');

            $.ajax({
                url: '{{ route("admin.wedding-templates.activate", "__ID__") }}'.replace('__ID__', templateId),
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message || 'Template berhasil diaktifkan!', 'success');
                        // Reload page to update active template banner and cards
                        setTimeout(() => { location.reload(); }, 800);
                    } else {
                        btn.prop('disabled', false).html(originalHtml);
                        showToast(response.message || 'Gagal mengaktifkan template', 'error');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html(originalHtml);
                    const msg = xhr.responseJSON?.message || 'Gagal mengaktifkan template';
                    showToast(msg, 'error');
                }
            });
        });

        // Note: Wedding templates are rendered server-side.
        // Activate button is handled above via delegated event.
    });
</script>
</body>
</html>
