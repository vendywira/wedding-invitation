<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Wedding Vendy & Margareth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">

    <style>
        :root {
            --primary: #e44d26;
            --primary-light: #f26161;
            --dark: #2d3748;
            --light: #f8f9fa;
            --success: #10b981;
            --info: #3b82f6;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        body {
            background: #f5f7f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 60px;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .sidebar {
            background: white;
            min-height: calc(100vh - 76px);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .nav-link {
            color: var(--dark);
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary);
            transition: transform 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .event-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 15px;
        }

        .event-header {
            padding: 20px;
            color: white;
        }

        .event-header.gedung {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .event-header.rumah {
            background: linear-gradient(135deg, #10b981, #047857);
        }

        .event-stats {
            padding: 20px;
        }

        .event-stat {
            text-align: center;
            padding: 15px;
        }

        .event-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .event-label {
            color: #6b7280;
            font-size: 0.8rem;
        }

        .progress-custom {
            height: 8px;
            border-radius: 10px;
            background: #e5e7eb;
        }

        .btn-primary-custom {
            background: var(--primary);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .btn-whatsapp {
            background: #25D366;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-whatsapp:hover {
            background: #128C7E;
            transform: translateY(-1px);
            color: white;
        }

        .table-custom {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .table-custom th {
            background: #f8f9fa;
            border: none;
            padding: 15px;
            font-weight: 600;
            color: var(--dark);
        }

        .table-custom td {
            padding: 15px;
            border-color: #f1f5f9;
        }

        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .share-btn-group {
            display: flex;
            gap: 5px;
        }

        .template-message {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid var(--primary);
        }

        .template-message pre {
            margin: 0;
            white-space: pre-wrap;
            font-family: inherit;
        }

        .modal-custom .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
        }

        .guest-share-actions {
            display: flex;
            gap: 5px;
            justify-content: flex-end;
        }

        .add-guest-card {
            margin-bottom: 20px;
        }

        /* Mobile Bottom Navigation */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            padding: 8px 0;
        }

        .mobile-nav-item {
            flex: 1;
            text-align: center;
            padding: 10px 5px;
            color: var(--dark);
            text-decoration: none;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }

        .mobile-nav-item.active {
            color: var(--primary);
        }

        .mobile-nav-item i {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 4px;
        }

        /* Loading indicator */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
        }

        /* Pull to refresh */
        .pull-to-refresh {
            text-align: center;
            padding: 10px;
            color: #6c757d;
            display: none;
        }

        /* Action buttons styling */
        .btn-edit {
            background: #3b82f6;
            border: none;
            color: white;
        }

        .btn-edit:hover {
            background: #2563eb;
            color: white;
        }

        /* ============================ */
        /* RESPONSIVE DESIGN - MOBILE */
        /* ============================ */

        @media (max-width: 768px) {
            /* Hide sidebar and hamburger menu */
            .sidebar {
                display: none;
            }

            .mobile-menu-btn {
                display: none !important;
            }

            /* Mobile bottom navigation */
            .mobile-bottom-nav {
                display: flex;
            }

            body {
                padding-bottom: 70px;
            }

            /* Container adjustments */
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }

            .col-lg-10.p-4 {
                padding: 15px !important;
            }

            /* Stat cards mobile optimization */
            .stat-card {
                padding: 18px 15px;
                margin-bottom: 15px;
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .stat-number {
                font-size: 1.7rem;
            }

            .stat-label {
                font-size: 0.85rem;
            }

            /* Stat cards grid layout dengan jarak yang baik */
            .row.mobile-stats {
                margin-left: -10px;
                margin-right: -10px;
                margin-bottom: 10px;
            }

            .row.mobile-stats > [class*="col-"] {
                padding-left: 10px;
                padding-right: 10px;
                margin-bottom: 8px;
            }

            /* Event cards mobile optimization */
            .event-card {
                margin-bottom: 20px;
                border-radius: 10px;
            }

            .event-header {
                padding: 15px;
            }

            .event-header h5 {
                font-size: 1.1rem;
                margin-bottom: 5px;
            }

            .event-header small {
                font-size: 0.8rem;
            }

            .event-stats {
                padding: 15px;
            }

            .event-stat {
                padding: 10px 5px;
                margin-bottom: 5px;
            }

            .event-number {
                font-size: 1.1rem;
                margin-bottom: 5px;
            }

            .event-label {
                font-size: 0.75rem;
            }

            .progress-custom {
                height: 6px;
            }

            /* Table responsive */
            .table-responsive {
                font-size: 0.8rem;
            }

            .btn-group-sm .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.775rem;
            }

            .guest-share-actions {
                flex-direction: column;
                gap: 2px;
            }

            .nav-link {
                padding: 10px 15px;
                font-size: 0.9rem;
            }

            /* Mobile table styling */
            .table-mobile-view {
                display: block;
            }

            .table-mobile-view thead {
                display: none;
            }

            .table-mobile-view tbody,
            .table-mobile-view tr,
            .table-mobile-view td {
                display: block;
                width: 100%;
            }

            .table-mobile-view tr {
                margin-bottom: 15px;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 10px;
                background: white;
            }

            .table-mobile-view td {
                border: none;
                padding: 8px 12px;
                position: relative;
                padding-left: 50%;
            }

            .table-mobile-view td:before {
                content: attr(data-label);
                position: absolute;
                left: 12px;
                width: 45%;
                padding-right: 10px;
                font-weight: 600;
                color: var(--dark);
                font-size: 0.8rem;
            }

            /* Toast container mobile */
            .toast-container {
                top: 70px;
                right: 10px;
                left: 10px;
            }

            /* Pull to refresh mobile */
            .pull-to-refresh {
                display: block;
            }

            /* Button adjustments */
            .btn-primary-custom, .btn-whatsapp {
                padding: 8px 15px;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 576px) {
            /* Container adjustments */
            .container-fluid {
                padding-left: 12px;
                padding-right: 12px;
            }

            .col-lg-10.p-4 {
                padding: 12px !important;
            }

            /* Stat cards small screen optimization */
            .stat-card {
                padding: 16px 12px;
                margin-bottom: 12px;
                border-radius: 8px;
            }

            .stat-number {
                font-size: 1.5rem;
                margin-bottom: 4px;
            }

            .stat-label {
                font-size: 0.8rem;
                margin-bottom: 3px;
            }

            .stat-card small.text-muted {
                font-size: 0.75rem;
            }

            /* Stat cards grid dengan jarak lebih compact */
            .row.mobile-stats {
                margin-left: -8px;
                margin-right: -8px;
                margin-bottom: 8px;
            }

            .row.mobile-stats > [class*="col-"] {
                padding-left: 8px;
                padding-right: 8px;
                margin-bottom: 6px;
            }

            /* Event cards small screen */
            .event-card {
                margin-bottom: 15px;
            }

            .event-header {
                padding: 12px 15px;
            }

            .event-header h5 {
                font-size: 1rem;
            }

            .event-stats {
                padding: 12px 15px;
            }

            .event-stat {
                padding: 8px 4px;
            }

            .event-number {
                font-size: 1rem;
            }

            .event-label {
                font-size: 0.7rem;
            }

            /* Button adjustments */
            .btn-primary-custom, .btn-whatsapp {
                padding: 8px 12px;
                font-size: 0.85rem;
            }

            /* Mobile bottom nav small screen */
            .mobile-nav-item {
                font-size: 0.7rem;
                padding: 8px 3px;
            }

            .mobile-nav-item i {
                font-size: 1rem;
            }
        }

        /* Untuk screen yang sangat kecil (iPhone SE dll) */
        @media (max-width: 375px) {
            .stat-card {
                padding: 14px 10px;
                margin-bottom: 10px;
            }

            .stat-number {
                font-size: 1.4rem;
            }

            .stat-label {
                font-size: 0.78rem;
            }

            .row.mobile-stats {
                margin-left: -6px;
                margin-right: -6px;
            }

            .row.mobile-stats > [class*="col-"] {
                padding-left: 6px;
                padding-right: 6px;
                margin-bottom: 5px;
            }

            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }

            .col-lg-10.p-4 {
                padding: 10px !important;
            }
        }

        /* Form validation styles */
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .was-validated .form-control:invalid ~ .invalid-feedback {
            display: block;
        }

        /* Custom scrollbar untuk webkit browsers */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Animation for pull to refresh */
        @keyframes pullRefresh {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(10px);
            }
            100% {
                transform: translateY(0);
            }
        }

        .pull-to-refresh.refreshing {
            animation: pullRefresh 1s ease-in-out infinite;
        }

        /* Hover effects for better UX */
        .btn-primary-custom:active,
        .btn-whatsapp:active {
            transform: translateY(0);
        }

        /* Focus states for accessibility */
        .btn-primary-custom:focus,
        .btn-whatsapp:focus,
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(228, 77, 38, 0.25);
            border-color: var(--primary);
        }

        /* Print styles */
        @media print {
            .mobile-bottom-nav,
            .navbar,
            .btn-primary-custom,
            .btn-whatsapp {
                display: none !important;
            }

            .stat-card,
            .event-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
        /* Filter Section Styles */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .filter-info {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.875rem;
        }

        /* Mobile responsive for filter */
        @media (max-width: 768px) {
            .filter-section {
                padding: 15px;
            }

            .filter-info {
                padding: 8px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
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
            <i class="fas fa-heart me-2"></i>Wedding Admin
        </a>
        <div class="navbar-nav ms-auto">
            <a href="/logout" class="nav-link text-white"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
            <form id="logout-form" action="/logout" method="POST" class="d-none">
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
                    <small class="text-muted">Vendy & Margareth</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#dashboard" data-bs-toggle="tab">
                            <i class="fas fa-chart-pie"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#guests" data-bs-toggle="tab">
                            <i class="fas fa-users"></i> Manage Tamu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#messages" data-bs-toggle="tab">
                            <i class="fas fa-comments"></i> Ucapan
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

                    <!-- Event Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="event-card">
                                <div class="event-header gedung">
                                    <h5 class="mb-1"><i class="fas fa-building me-2"></i>{{
                                        $eventStats['gedung']['name'] }}</h5>
                                    <small><span
                                            id="gedungAllGuests">{{ $eventStats['gedung']['all_guests_count'] }}</span>
                                        tamu diundang</small>
                                </div>
                                <div class="event-stats">
                                    <div class="row text-center">
                                        <div class="col-4 event-stat">
                                            <div class="event-number text-primary" id="gedungTotalGuests">{{
                                                $eventStats['gedung']['total_guests'] }}
                                            </div>
                                            <div class="event-label">Tamu Hadir</div>
                                        </div>
                                        <div class="col-4 event-stat">
                                            <div class="event-number text-success" id="gedungGuestAttends">{{
                                                $eventStats['gedung']['guest_attends_total'] }}
                                            </div>
                                            <div class="event-label">Total Orang</div>
                                        </div>
                                        <div class="col-4 event-stat">
                                            <div class="event-number text-info" id="gedungTotalMessages">{{
                                                $eventStats['gedung']['total_messages'] }}
                                            </div>
                                            <div class="event-label">Ucapan</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">Progress Konfirmasi</small>
                                        <div class="progress progress-custom mt-1">
                                            @php
                                            $gedungProgress = $eventStats['gedung']['all_guests_count'] > 0 ?
                                            (($eventStats['gedung']['attending_guests'] +
                                            $eventStats['gedung']['not_attending_guests']) /
                                            $eventStats['gedung']['all_guests_count']) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $gedungProgress }}%"
                                                 id="gedungProgressBar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="event-card">
                                <div class="event-header rumah">
                                    <h5 class="mb-1"><i class="fas fa-home me-2"></i>{{ $eventStats['rumah']['name'] }}
                                    </h5>
                                    <small><span
                                            id="rumahAllGuests">{{ $eventStats['rumah']['all_guests_count'] }}</span>
                                        tamu diundang</small>
                                </div>
                                <div class="event-stats">
                                    <div class="row text-center">
                                        <div class="col-4 event-stat">
                                            <div class="event-number text-primary" id="rumahTotalGuests">{{
                                                $eventStats['rumah']['total_guests'] }}
                                            </div>
                                            <div class="event-label">Tamu Hadir</div>
                                        </div>
                                        <div class="col-4 event-stat">
                                            <div class="event-number text-success" id="rumahGuestAttends">{{
                                                $eventStats['rumah']['guest_attends_total'] }}
                                            </div>
                                            <div class="event-label">Total Orang</div>
                                        </div>
                                        <div class="col-4 event-stat">
                                            <div class="event-number text-info" id="rumahTotalMessages">{{
                                                $eventStats['rumah']['total_messages'] }}
                                            </div>
                                            <div class="event-label">Ucapan</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">Progress Konfirmasi</small>
                                        <div class="progress progress-custom mt-1">
                                            @php
                                            $rumahProgress = $eventStats['rumah']['all_guests_count'] > 0 ?
                                            (($eventStats['rumah']['attending_guests'] +
                                            $eventStats['rumah']['not_attending_guests']) /
                                            $eventStats['rumah']['all_guests_count']) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $rumahProgress }}%"
                                                 id="rumahProgressBar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                        <div class="col-md-4 col-12">
                                            <input type="text" name="name" class="form-control form-control-sm"
                                                   placeholder="Nama tamu" required id="guestNameInput">
                                            <div class="form-text text-danger d-none" id="nameError">
                                                Nama sudah terdaftar untuk acara ini
                                            </div>
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
                                                <option value="p">Gedung</option>
                                                <option value="r">Rumah</option>
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
                                                <div class="col-md-4 col-6">
                                                    <select class="form-control form-control-sm" id="eventFilter">
                                                        <option value="all">Semua Acara</option>
                                                        <option value="gedung">Gedung</option>
                                                        <option value="rumah">Rumah</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <select class="form-control form-control-sm" id="statusFilter">
                                                        <option value="all">Semua Status</option>
                                                        <option value="Hadir">Hadir</option>
                                                        <option value="Tidak Hadir">Tidak Hadir</option>
                                                        <option value="pending">Belum Konfirmasi</option>
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
                                    <th>Acara</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Link</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody id="guestsTableBody">
                                @foreach($guests as $guest)
                                <tr data-guest-id="{{ $guest->id }}"
                                    data-event-type="{{ $guest->event ? $guest->event->event_key : 'gedung' }}"
                                    data-attendance="{{ $guest->attendance }}">
                                    <td>
                                        <strong>{{ $guest->name }}</strong>
                                    </td>
                                    <td>
                                        @if($guest->event)
                                        <span class="badge {{ $guest->event->event_key === 'rumah' ? 'bg-success' : 'bg-primary' }} badge-custom">
                            {{ $guest->event->event_key }}
                        </span>
                                        @else
                                        <span class="badge bg-secondary badge-custom">-</span>
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
                                        @php
                                        $baseUrl = url('/');
                                        $eventKey = $guest->event ? $guest->event->event_key : 'gedung';
                                        $path = $eventKey === 'rumah' ? 'r' : 'p';
                                        $invitationUrl = "{$baseUrl}/{$path}/invitation?to=" . urlencode($guest->name);
                                        @endphp
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-sm btn-whatsapp share-guest-whatsapp"
                                                    data-name="{{ $guest->name }}"
                                                    data-event="{{ $guest->event }}"
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
                                                    data-event-type="{{ $guest->event ? $guest->event->event_key : 'gedung' }}"
                                                    data-attendance="{{ $guest->attendance }}"
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
                                $baseUrl = url('/');
                                $eventKey = $guest->event ? $guest->event->event_key : 'gedung';
                                $path = $eventKey === 'rumah' ? 'r' : 'p';
                                $invitationUrl = "{$baseUrl}/{$path}/invitation?to=" . urlencode($guest->name);;
                                @endphp
                                <div class="card mb-3" data-guest-id="{{ $guest->id }}"
                                     data-event-type="{{ $guest->event ? $guest->event->event_key : 'gedung' }}"
                                     data-attendance="{{ $guest->attendance }}">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $guest->name }}</h6>
                                        <p class="card-text mb-1">
                                            <strong>Acara:</strong>
                                            @if($guest->event)
                                            <span class="badge {{ $guest->event->event_key === 'rumah' ? 'bg-success' : 'bg-primary' }} badge-custom">
                                {{ $guest->event->event_key }}
                            </span>
                                            @else
                                            <span class="badge bg-secondary badge-custom">-</span>
                                            @endif
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
                                        <div class="btn-group w-100 mt-2">
                                            <button class="btn btn-sm btn-whatsapp share-guest-whatsapp"
                                                    data-name="{{ $guest->name }}"
                                                    data-event="{{ $guest->event }}"
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
                                                    data-event-type="{{ $guest->event ? $guest->event->event_key : 'gedung' }}"
                                                    data-attendance="{{ $guest->attendance }}"
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
            </div>
        </div>
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<div class="mobile-bottom-nav">
    <a href="#dashboard" class="mobile-nav-item active" data-bs-toggle="tab">
        <i class="fas fa-chart-pie"></i>
        <span>Dashboard</span>
    </a>
    <a href="#guests" class="mobile-nav-item" data-bs-toggle="tab">
        <i class="fas fa-users"></i>
        <span>Tamu</span>
    </a>
    <a href="#messages" class="mobile-nav-item" data-bs-toggle="tab">
        <i class="fas fa-comments"></i>
        <span>Ucapan</span>
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
                    <label class="form-label">Template Pesan</label>
                    <textarea class="form-control" id="modalMessageTemplate" rows="6" readonly></textarea>
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
                            <option value="p">Gedung</option>
                            <option value="r">Rumah</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Konfirmasi</label>
                        <select class="form-control" id="editAttendance" name="attendance">
                            <option value="">Belum Konfirmasi</option>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Variables for auto-refresh
        let refreshInterval;
        let lastUpdateTime = new Date();
        let isRefreshing = false;

        // Initialize auto-refresh
        // initAutoRefresh();

        // Tab functionality
        $('.nav-link, .mobile-nav-item').on('click', function(e) {
            e.preventDefault();
            const target = $(this).attr('href');

            // Update active states
            $('.nav-link').removeClass('active');
            $('.mobile-nav-item').removeClass('active');
            $(this).addClass('active');

            // Find corresponding nav link for mobile nav items
            if ($(this).hasClass('mobile-nav-item')) {
                $(`.nav-link[href="${target}"]`).addClass('active');
            } else {
                $(`.mobile-nav-item[href="${target}"]`).addClass('active');
            }

            // Show target tab
            $('.tab-pane').removeClass('show active');
            $(target).addClass('show active');
        });

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
                        showToast('Tamu berhasil ditambahkan!', 'success');
                        if (response.invitation_url) {
                            setTimeout(() => {
                                if (confirm('Link undangan berhasil dibuat! Copy ke clipboard?')) {
                                    navigator.clipboard.writeText(response.invitation_url);
                                }
                            }, 500);
                        }
                        // Reset form
                        $('#addGuestForm')[0].reset();
                        // Refresh guests data
                        refreshGuestsData();
                        // Refresh dashboard stats
                        refreshDashboardData();
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        // Validation error
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
            });
        });

        // Delete guest
        $(document).on('click', '.delete-guest', function() {
            const guestId = $(this).data('id');
            const guestName = $(this).data('name');

            if (confirm(`Hapus tamu "${guestName}"?`)) {
                showLoading();
                $.ajax({
                    url: `/admin/guests/${guestId}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        hideLoading();
                        if (response.success) {
                            showToast('Tamu berhasil dihapus!', 'success');
                            // Remove guest from UI
                            $(`[data-guest-id="${guestId}"]`).remove();
                            // Refresh stats
                            refreshDashboardData();
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        showToast('Error menghapus tamu', 'error');
                    }
                });
            }
        });

        // Delete message
        $(document).on('click', '.delete-message', function() {
            const messageId = $(this).data('id');
            const messageName = $(this).data('name');

            if (confirm(`Hapus ucapan dari "${messageName}"?`)) {
                showLoading();
                $.ajax({
                    url: `/admin/messages/${messageId}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        hideLoading();
                        if (response.success) {
                            showToast('Ucapan berhasil dihapus!', 'success');
                            // Remove message from UI
                            $(`[data-message-id="${messageId}"]`).remove();
                            // Refresh stats
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

        // Edit guest functionality
        $(document).on('click', '.edit-guest', function() {
            const guestId = $(this).data('id');
            const guestName = $(this).data('name');
            const guestAttends = $(this).data('guest-attends');
            const eventType = $(this).data('event-type');
            const attendance = $(this).data('attendance');

            $('#editGuestId').val(guestId);
            $('#editGuestName').val(guestName);
            $('#editGuestAttends').val(guestAttends);
            $('#editEventType').val(eventType);
            $('#editAttendance').val(attendance || '');

            $('#editGuestModal').modal('show');
        });

        // Save edited guest
        $('#saveEditGuest').on('click', function() {
            const formData = {
                _token: '{{ csrf_token() }}',
                name: $('#editGuestName').val(),
                guest_attends: $('#editGuestAttends').val(),
                event_type: $('#editEventType').val(),
                attendance: $('#editAttendance').val()
            };

            const guestId = $('#editGuestId').val();
            showLoading();

            $.ajax({
                url: `/admin/guests/${guestId}`,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        showToast('Data tamu berhasil diperbarui!', 'success');
                        $('#editGuestModal').modal('hide');
                        // Refresh guests data
                        refreshGuestsData();
                        // Refresh dashboard stats
                        refreshDashboardData();
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        // Validation error
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

        // Share guest via WhatsApp
        $(document).on('click', '.share-guest-whatsapp', function() {
            const guestName = $(this).data('name');
            const event = $(this).data('event')

            const baseUrl = '{{ url("/") }}';
            const path = event.event_key === 'rumah' ? 'r' : 'p';
            const invitationLink = `${baseUrl}/${path}/invitation?to=${encodeURIComponent(guestName)}`;

            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            };

            const formatter = new Intl.DateTimeFormat('id-ID', options);
            const formattedDate = formatter.format(new Date(event.event_date));
            const eventTime = `${event.start_time} WITA - ${event.finish_time}`;
            const eventLocation = event.location;

            // Create WhatsApp-compatible message template
            const messageTemplate =
                `*UNDANGAN PERNIKAHAN*

Kepada Yth. ${guestName}

Dengan penuh sukacita, kami mengundang Bapak/Ibu/Saudara/i untuk hadir dalam acara pernikahan kami:

*Vendy & Margareth*
📅 *${formattedDate}*
⏰ *${eventTime}*
📌 *${eventLocation}*

Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.

Konfirmasi kehadiran: ${invitationLink}

*Terima kasih,*
*Vendy & Margareth*`;

            // Show modal with options
            $('#modalGuestName').val(guestName);
            $('#modalInvitationLink').val(invitationLink);
            $('#modalMessageTemplate').val(messageTemplate);

            // Set up modal share button
            $('#modalShareWhatsApp').off('click').on('click', function() {
                const encodedMessage = encodeURIComponent(messageTemplate);
                const whatsappUrl = `https://wa.me/?text=${encodedMessage}`;
                window.open(whatsappUrl, '_blank');
                $('#shareModal').modal('hide');
            });

            // Set up modal copy link button
            $('#modalCopyLink').off('click').on('click', function() {
                navigator.clipboard.writeText(invitationLink).then(function() {
                    showToast('Link berhasil disalin!', 'success');
                });
            });

            $('#shareModal').modal('show');
        });

        // Manual refresh buttons
        $('#refreshGuests').on('click', function() {
            refreshGuestsData();
        });

        $('#refreshMessages').on('click', function() {
            refreshMessagesData();
        });

        // HAPUS PULL-TO-REFRESH UNTUK MANAGE TAMU DAN MANAGE MESSAGE
        // Hanya aktif di dashboard tab
        let startY = 0;
        let currentY = 0;
        let pullDelta = 0;
        const pullToRefresh = $('#pullToRefresh');
        const pullThreshold = 60;

        document.addEventListener('touchstart', (e) => {
            // Hanya aktif jika di dashboard tab
            if (window.scrollY === 0 && $('#dashboard').hasClass('active')) {
                startY = e.touches[0].clientY;
                pullToRefresh.css('display', 'block');
            }
        }, { passive: true });

        document.addEventListener('touchmove', (e) => {
            // Hanya aktif jika di dashboard tab
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
            // Hanya aktif jika di dashboard tab
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

        // Auto-refresh functions
        // function initAutoRefresh() {
        //     // Refresh every 30 seconds
        //     refreshInterval = setInterval(() => {
        //         if (!isRefreshing && document.visibilityState === 'visible') {
        //             refreshCurrentTab();
        //         }
        //     }, 30000);
        // }

        function refreshCurrentTab() {
            const activeTab = $('.tab-pane.active').attr('id');

            switch(activeTab) {
                case 'dashboard':
                    refreshDashboardData();
                    break;
                case 'guests':
                    refreshGuestsData();
                    break;
                case 'messages':
                    refreshMessagesData();
                    break;
            }
        }

        function refreshDashboardData() {
            if (isRefreshing) return;
            isRefreshing = true;

            $.ajax({
                url: '{{ route("admin.dashboard.data") }}',
                type: 'GET',
                success: function(response) {
                    // Update stats
                    $('#totalGuests').text(response.stats.total_guests);
                    $('#allGuestsCount').text(response.stats.all_guests_count);
                    $('#totalPeople').text(response.stats.total_people);
                    $('#totalMessages').text(response.stats.total_messages);
                    $('#pendingGuests').text(response.stats.pending_guests);

                    // Update event stats
                    $('#gedungAllGuests').text(response.eventStats.gedung.all_guests_count);
                    $('#gedungTotalGuests').text(response.eventStats.gedung.total_guests);
                    $('#gedungGuestAttends').text(response.eventStats.gedung.guest_attends_total);
                    $('#gedungTotalMessages').text(response.eventStats.gedung.total_messages);

                    $('#rumahAllGuests').text(response.eventStats.rumah.all_guests_count);
                    $('#rumahTotalGuests').text(response.eventStats.rumah.total_guests);
                    $('#rumahGuestAttends').text(response.eventStats.rumah.guest_attends_total);
                    $('#rumahTotalMessages').text(response.eventStats.rumah.total_messages);

                    // Update progress bars
                    const gedungProgress = response.eventStats.gedung.all_guests_count > 0 ?
                        ((response.eventStats.gedung.attending_guests + response.eventStats.gedung.not_attending_guests) / response.eventStats.gedung.all_guests_count) * 100 : 0;
                    $('#gedungProgressBar').css('width', `${gedungProgress}%`);

                    const rumahProgress = response.eventStats.rumah.all_guests_count > 0 ?
                        ((response.eventStats.rumah.attending_guests + response.eventStats.rumah.not_attending_guests) / response.eventStats.rumah.all_guests_count) * 100 : 0;
                    $('#rumahProgressBar').css('width', `${rumahProgress}%`);

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
                success: function(response) {
                    // Update desktop table
                    $('#guestsTableBody').html(response.desktopView);

                    // Update mobile view
                    $('#mobileGuestsList').html(response.mobileView);

                    hideLoading();
                    lastUpdateTime = new Date();
                    isRefreshing = false;
                    showToast('Data tamu diperbarui', 'success');
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
                success: function(response) {
                    $('#messagesTableBody').html(response.messages);
                    hideLoading();
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

        // Utility functions
        function showLoading() {
            $('#loadingOverlay').fadeIn();
        }

        function hideLoading() {
            $('#loadingOverlay').fadeOut();
        }

        function showToast(message, type = 'info') {
            const toastId = 'toast-' + Date.now();
            const bgClass = type === 'success' ? 'bg-success' :
                type === 'error' ? 'bg-danger' :
                    type === 'warning' ? 'bg-warning' : 'bg-info';

            const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

            $('#toastContainer').append(toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
            toast.show();

            // Remove toast from DOM after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                $(this).remove();
            });
        }

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
    });

    // Filter functionality
    $('#eventFilter, #statusFilter').on('change', function() {
        applyFilters();
    });

    $('#resetFilter').on('click', function() {
        $('#eventFilter').val('all');
        $('#statusFilter').val('all');
        applyFilters();
    });

    function applyFilters() {
        const eventFilter = $('#eventFilter').val();
        const statusFilter = $('#statusFilter').val();

        let visibleCount = 0;
        let totalCount = 0;

        // Filter desktop table
        $('#guestsTableBody tr').each(function() {
            const eventType = $(this).data('event-type');
            const attendance = $(this).data('attendance');

            let showRow = true;

            // Apply event filter
            if (eventFilter !== 'all' && eventType !== eventFilter) {
                showRow = false;
            }

            // Apply status filter
            if (statusFilter !== 'all') {
                if (statusFilter === 'pending' && attendance !== '') {
                    showRow = false;
                } else if (statusFilter !== 'pending' && attendance !== statusFilter) {
                    showRow = false;
                }
            }

            if (showRow) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
            totalCount++;
        });

        // Filter mobile view
        $('#mobileGuestsList .card').each(function() {
            const eventType = $(this).data('event-type');
            const attendance = $(this).data('attendance');

            let showCard = true;

            // Apply event filter
            if (eventFilter !== 'all' && eventType !== eventFilter) {
                showCard = false;
            }

            // Apply status filter
            if (statusFilter !== 'all') {
                if (statusFilter === 'pending' && attendance !== '') {
                    showCard = false;
                } else if (statusFilter !== 'pending' && attendance !== statusFilter) {
                    showCard = false;
                }
            }

            if (showCard) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        // Update count display
        $('#filteredCount').text(visibleCount);
        $('#totalCount').text(totalCount);

        // Update filter info
        updateFilterInfo(eventFilter, statusFilter);
    }

    function updateFilterInfo(eventFilter, statusFilter) {
        let infoText = '';

        if (eventFilter !== 'all') {
            infoText += `Acara: ${eventFilter === 'gedung' ? 'Gedung' : 'Rumah'}`;
        }

        if (statusFilter !== 'all') {
            if (infoText !== '') infoText += ' | ';
            infoText += `Status: ${statusFilter === 'pending' ? 'Belum Konfirmasi' : statusFilter}`;
        }

        if (infoText === '') {
            infoText = 'Semua tamu ditampilkan';
        }

        $('#filterInfo').text(infoText);
    }

    // Export filtered data
    $('#exportFiltered').on('click', function() {
        const eventFilter = $('#eventFilter').val();
        const statusFilter = $('#statusFilter').val();

        let url = '{{ route("admin.guests.export.filtered") }}';
        url += `?event=${eventFilter}&status=${statusFilter}`;

        window.location.href = url;
    });

    // Update counts when refreshing guests data
    function updateGuestCounts() {
        const totalCount = $('#guestsTableBody tr').length;
        const visibleCount = $('#guestsTableBody tr:visible').length;

        $('#filteredCount').text(visibleCount);
        $('#totalCount').text(totalCount);
    }

    // Update filter info on page load
    $(document).ready(function() {
        updateFilterInfo('all', 'all');
    });
</script>
</body>
</html>
