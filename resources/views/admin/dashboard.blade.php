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
        /* Tambahkan di bagian CSS yang responsive */
        @media (max-width: 768px) {
            .input-group-sm .input-group-text {
                padding: 0.375rem 0.5rem;
                font-size: 0.775rem;
            }

            #searchFilter {
                font-size: 0.875rem;
            }
        }

        /* Styling untuk highlight hasil pencarian */
        .highlight {
            background-color: #fff3cd;
            font-weight: bold;
            padding: 2px 4px;
            border-radius: 3px;
        }
        /* Template Management Enhanced Styles */
        .template-management {
            max-height: 70vh;
            overflow-y: auto;
        }

        .template-form-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .template-list-container {
            background: white;
            border-radius: 10px;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        #templatesTable {
            margin-bottom: 0;
        }

        #templatesTable th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: var(--dark);
        }

        #templatesTable td {
            vertical-align: middle;
            padding: 12px 15px;
        }

        .template-actions {
            white-space: nowrap;
        }

        .template-variables-hint {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .template-variables-hint code {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85em;
            margin: 0 2px;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .template-management {
                max-height: 60vh;
            }

            .template-form-container {
                padding: 15px;
            }

            #templatesTable td {
                padding: 8px 10px;
                font-size: 0.9rem;
            }

            .template-actions .btn-group {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .template-actions .btn {
                padding: 4px 8px;
                font-size: 0.8rem;
            }
        }

        /* Loading state for templates */
        .template-loading {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .template-empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .template-empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Form styling enhancements */
        #addTemplateForm .form-check {
            margin-bottom: 10px;
        }

        #addTemplateForm .form-check-label {
            font-weight: 500;
        }

        #addTemplateForm textarea {
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Elegant Confirmation Modal Styles */
        #elegantConfirmModal .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        #elegantConfirmModal .modal-header {
            padding: 1.5rem 1.5rem 0;
        }

        #elegantConfirmModal .modal-body {
            padding: 0 1.5rem 1.5rem;
        }

        .confirm-icon {
            font-size: 4rem;
            animation: pulse 2s infinite;
        }

        .confirm-icon .fa-exclamation-circle {
            color: #ffc107;
            filter: drop-shadow(0 4px 8px rgba(255, 193, 7, 0.3));
        }

        .success-icon .fa-check-circle {
            filter: drop-shadow(0 4px 8px rgba(255, 255, 255, 0.3));
        }

        #elegantConfirmModal .btn {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        #elegantConfirmModal .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
        }

        #elegantConfirmModal .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
            transform: translateY(-2px);
        }

        #elegantConfirmModal .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border: none;
            box-shadow: 0 4px 15px rgba(228, 77, 38, 0.3);
        }

        #elegantConfirmModal .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(228, 77, 38, 0.4);
        }

        /* Success Toast Modal */
        #successToastModal .modal-content {
            border-radius: 16px;
            border: none;
            animation: slideInUp 0.5s ease;
        }

        #successToastModal .modal-body {
            border-radius: 16px;
        }

        /* Animations */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes slideInUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            50% {
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Different icon colors for different actions */
        .confirm-icon.delete .fa-exclamation-circle {
            color: #dc3545;
            filter: drop-shadow(0 4px 8px rgba(220, 53, 69, 0.3));
        }

        .confirm-icon.warning .fa-exclamation-circle {
            color: #fd7e14;
            filter: drop-shadow(0 4px 8px rgba(253, 126, 20, 0.3));
        }

        .confirm-icon.info .fa-exclamation-circle {
            color: #0dcaf0;
            filter: drop-shadow(0 4px 8px rgba(13, 202, 240, 0.3));
        }

        .confirm-icon.success .fa-exclamation-circle {
            color: #198754;
            filter: drop-shadow(0 4px 8px rgba(25, 135, 84, 0.3));
        }

        /* Responsive design */
        @media (max-width: 576px) {
            #elegantConfirmModal .modal-dialog {
                margin: 20px;
            }

            #elegantConfirmModal .modal-body {
                padding: 0 1rem 1rem;
            }

            #elegantConfirmModal .btn {
                padding: 10px 16px;
                font-size: 0.9rem;
            }

            .confirm-icon {
                font-size: 3rem;
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
            <i class="fas fa-grip me-2"></i>Dashboard
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
                                                <!-- Input Search Baru -->
                                                <div class="col-md-3 col-12">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                        <input type="text" class="form-control" id="searchFilter" placeholder="Cari nama...">
                                                    </div>
                                                </div>
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
                            <button class="btn btn-primary-custom me-2" id="manageTemplate"">
                                <i class="fas fa-envelope me-1"></i>
                            </button>
                            <button class="btn btn-primary-custom me-2" id="refreshGuests" >
                                <i class="fas fa-sync-alt me-1"></i>
                            </button>
                            <button class="btn btn-primary-custom me-2" id="exportFiltered">
                                <i class="fas fa-download me-1"></i>
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
                                    data-attendance="{{ $guest->attendance ?? 'Belum Konfirmasi' }}">
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
                                                    data-attendance="{{ $guest->attendance ?? 'Belum Konfirmasi' }}"
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
                                                    data-attendance="{{ $guest->attendance ?? '' }}"
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
                            <option value="p">Gedung</option>
                            <option value="r">Rumah</option>
                        </select>
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

        // ==================== TAB FUNCTIONALITY ====================
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

        // Delete guest
        $(document).on('click', '.delete-guest', function() {
            const guestId = $(this).data('id');
            const guestName = $(this).data('name');

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
                        url: `/admin/guests/${guestId}`,
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
            const guestId = $(this).data('id');
            const guestName = $(this).data('name');
            const guestAttends = $(this).data('guest-attends');
            const eventType = $(this).data('event-type');
            let attendance = $(this).data('attendance');

            let formattedEventType = 'p'; // default
            if (eventType === 'rumah') {
                formattedEventType = 'r';
            } else if (eventType === 'gedung') {
                formattedEventType = 'p';
            }

            // Handle attendance yang null/undefined
            if (!attendance || attendance === 'null' || attendance === 'undefined') {
                attendance = 'Belum Konfirmasi';
            }

            $('#editGuestId').val(guestId);
            $('#editGuestName').val(guestName);
            $('#editGuestAttends').val(guestAttends);
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
                event_type: $('#editEventType').val(),
                attendance: $('#editAttendance').val() || 'Belum Konfirmasi'
            };

            if (!validateEditForm()) {
                return;
            }

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
            const messageId = $(this).data('id');
            const messageName = $(this).data('name');

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
                        url: `/admin/messages/${messageId}`,
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
                // Escape template content untuk JSON
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

                    // Set template default
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

        // Add template form
        $('#addTemplateForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                _token: '{{ csrf_token() }}',
                name: $(this).find('input[name="name"]').val(),
                template: $(this).find('textarea[name="template"]').val(),
                is_active: $(this).find('#isActive').is(':checked') ? 1 : 0,
                is_default: $(this).find('#isDefault').is(':checked') ? 1 : 0
            };

            // Validasi
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
                url: '{{ route("admin.templates.store") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        showToast('Template berhasil ditambahkan', 'success');
                        $('#addTemplateForm')[0].reset();
                        loadAllTemplates();
                        loadTemplates(); // Refresh templates di share modal juga
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
                        showToast('Error menambahkan template', 'error');
                    }
                    console.error('Error adding template:', xhr);
                }
            });
        });

        // Edit template functionality
        $(document).on('click', '.edit-template', function() {
            const templateJson = $(this).data('template');
            const template = typeof templateJson === 'string' ?
                JSON.parse(templateJson.replace(/&#39;/g, "'").replace(/&quot;/g, '"')) : templateJson;

            // Isi form edit
            $('#addTemplateForm input[name="name"]').val(template.name);
            $('#addTemplateForm textarea[name="template"]').val(template.template);
            $('#addTemplateForm #isActive').prop('checked', template.is_active);
            $('#addTemplateForm #isDefault').prop('checked', template.is_default);

            // Ubah form menjadi edit mode
            $('#addTemplateForm').data('edit-mode', true);
            $('#addTemplateForm').data('edit-id', template.id);
            $('#addTemplateForm button[type="submit"]').html('<i class="fas fa-save me-1"></i> Update Template');

            // Scroll ke form
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
                        url: `/templates/${templateId}/set-default`,
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
                        url: `/templates/${templateId}`,
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

            // Replace variables
            message = message.replace(/{guest_name}/g, guestName || 'Nama Tamu');
            message = message.replace(/{groom_name}/g, 'Vendy');
            message = message.replace(/{bride_name}/g, 'Margareth');
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

            // Simpan data event di modal untuk digunakan nanti
            $('#shareModal').data('event-data', {
                formattedDate,
                eventTime,
                eventLocation
            });

            $('#modalGuestName').val(guestName);
            $('#modalInvitationLink').val(invitationLink);

            // Load templates dan buka modal
            loadTemplates();
            $('#shareModal').modal('show');
        });

        // Update modal share WhatsApp button
        $('#modalShareWhatsApp').on('click', function() {
            if (!currentTemplate) {
                showToast('Pilih template terlebih dahulu', 'error');
                return;
            }

            const message = $('#modalMessageTemplate').val();
            if (!message.trim()) {
                showToast('Pesan tidak boleh kosong', 'error');
                return;
            }

            const encodedMessage = encodeURIComponent(message);
            const whatsappUrl = `https://wa.me/?text=${encodedMessage}`;
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

            // Filter desktop table
            $('#guestsTableBody tr').each(function() {
                const eventType = $(this).data('event-type');
                let attendance = $(this).data('attendance');
                const guestName = $(this).find('td:first strong').text().toLowerCase();

                // Normalize attendance data
                if (!attendance || attendance === 'null' || attendance === 'undefined') {
                    attendance = 'Belum Konfirmasi';
                }

                let showRow = true;

                // Apply search filter
                if (searchTerm !== '' && !guestName.includes(searchTerm)) {
                    showRow = false;
                }

                // Apply event filter - handle both 'gedung'/'rumah' and 'p'/'r'
                if (eventFilter !== 'all') {
                    let normalizedEventType = eventType;

                    // Convert 'p' to 'gedung' and 'r' to 'rumah' for comparison
                    if (eventType === 'p') normalizedEventType = 'gedung';
                    if (eventType === 'r') normalizedEventType = 'rumah';

                    if (normalizedEventType !== eventFilter) {
                        showRow = false;
                    }
                }

                // Apply status filter
                if (statusFilter !== 'all') {
                    if (statusFilter === 'Belum Konfirmasi') {
                        // Handle various representations of "Belum Konfirmasi"
                        if (attendance && attendance !== '' && attendance !== 'Belum Konfirmasi') {
                            showRow = false;
                        }
                    } else if (attendance !== statusFilter) {
                        showRow = false;
                    }
                }

                if (showRow) {
                    $(this).show();
                    visibleCount++;

                    // Highlight search term jika ada
                    if (searchTerm !== '') {
                        highlightSearchTerm($(this), searchTerm);
                    } else {
                        removeHighlight($(this));
                    }
                } else {
                    $(this).hide();
                    removeHighlight($(this));
                }
                totalCount++;
            });

            // Filter mobile view
            $('#mobileGuestsList .card').each(function() {
                const eventType = $(this).data('event-type');
                let attendance = $(this).data('attendance');
                const guestName = $(this).find('.card-title').text().toLowerCase();

                // Normalize attendance data
                if (!attendance || attendance === 'null' || attendance === 'undefined') {
                    attendance = 'Belum Konfirmasi';
                }

                let showCard = true;

                // Apply search filter
                if (searchTerm !== '' && !guestName.includes(searchTerm)) {
                    showCard = false;
                }

                // Apply event filter - handle both 'gedung'/'rumah' and 'p'/'r'
                if (eventFilter !== 'all') {
                    let normalizedEventType = eventType;

                    // Convert 'p' to 'gedung' and 'r' to 'rumah' for comparison
                    if (eventType === 'p') normalizedEventType = 'gedung';
                    if (eventType === 'r') normalizedEventType = 'rumah';

                    if (normalizedEventType !== eventFilter) {
                        showCard = false;
                    }
                }

                // Apply status filter
                if (statusFilter !== 'all') {
                    if (statusFilter === 'Belum Konfirmasi') {
                        // Handle various representations of "Belum Konfirmasi"
                        if (attendance && attendance !== '' && attendance !== 'Belum Konfirmasi') {
                            showCard = false;
                        }
                    } else if (attendance !== statusFilter) {
                        showCard = false;
                    }
                }

                if (showCard) {
                    $(this).show();

                    // Highlight search term jika ada
                    if (searchTerm !== '') {
                        highlightSearchTermMobile($(this), searchTerm);
                    } else {
                        removeHighlightMobile($(this));
                    }
                } else {
                    $(this).hide();
                    removeHighlightMobile($(this));
                }
            });

            // Update count display
            $('#filteredCount').text(visibleCount);
            $('#totalCount').text(totalCount);

            // Update filter info
            updateFilterInfo(eventFilter, statusFilter, searchTerm);
        }

        // Fungsi untuk highlight teks di desktop view
        function highlightSearchTerm($row, searchTerm) {
            const $nameCell = $row.find('td:first strong');
            const originalText = $nameCell.data('original-text') || $nameCell.text();
            $nameCell.data('original-text', originalText);

            const highlightedText = originalText.replace(
                new RegExp(searchTerm, 'gi'),
                match => `<span class="highlight">${match}</span>`
            );
            $nameCell.html(highlightedText);
        }

        function removeHighlight($row) {
            const $nameCell = $row.find('td:first strong');
            const originalText = $nameCell.data('original-text');
            if (originalText) {
                $nameCell.text(originalText);
                $nameCell.removeData('original-text');
            }
        }

        // Fungsi untuk highlight teks di mobile view
        function highlightSearchTermMobile($card, searchTerm) {
            const $nameElement = $card.find('.card-title');
            const originalText = $nameElement.data('original-text') || $nameElement.text();
            $nameElement.data('original-text', originalText);

            const highlightedText = originalText.replace(
                new RegExp(searchTerm, 'gi'),
                match => `<span class="highlight">${match}</span>`
            );
            $nameElement.html(highlightedText);
        }

        function removeHighlightMobile($card) {
            const $nameElement = $card.find('.card-title');
            const originalText = $nameElement.data('original-text');
            if (originalText) {
                $nameElement.text(originalText);
                $nameElement.removeData('original-text');
            }
        }

        // Update fungsi updateFilterInfo untuk menampilkan info pencarian
        function updateFilterInfo(eventFilter, statusFilter, searchTerm = '') {
            let infoText = '';

            if (searchTerm !== '') {
                infoText += `Pencarian: "${searchTerm}"`;
            }

            if (eventFilter !== 'all') {
                if (infoText !== '') infoText += ' | ';
                infoText += `Acara: ${eventFilter === 'gedung' ? 'Resepsi Gedung' : 'Resepsi Rumah'}`;
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

        // Update counts when refreshing guests data
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
                    // Update filter counts
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
                filterInfo += `Acara: ${eventFilter === 'gedung' ? 'Gedung' : 'Rumah'}`;
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
            // Load templates untuk siap digunakan
            loadTemplates();

            // Update filter info
            updateFilterInfo('all', 'all');

            // Update guest counts
            updateGuestCounts();

            console.log('Admin dashboard initialized successfully');
        }

        // ==================== ELEGANT CONFIRM SYSTEM ====================
        let currentConfirmAction = null;
        let currentConfirmData = null;

// Fungsi confirm yang elegan
        function showElegantConfirm(message, options = {}) {
            return new Promise((resolve) => {
                const {
                    title = 'Konfirmasi',
                    type = 'warning', // warning, delete, info, success
                    confirmText = 'Ya, Lanjutkan',
                    cancelText = 'Batal',
                    icon = 'exclamation-circle'
                } = options;

                // Set modal content
                $('#elegantConfirmModalLabel').text(title);
                $('#confirmMessage').html(message);
                $('#confirmActionBtn').html(`<i class="fas fa-${icon} me-2"></i>${confirmText}`);
                $('#elegantConfirmModal .btn-outline-secondary').html(`<i class="fas fa-times me-2"></i>${cancelText}`);

                // Set icon type
                const $confirmIcon = $('#elegantConfirmModal .confirm-icon');
                $confirmIcon.removeClass('warning delete info success').addClass(type);
                $confirmIcon.find('i').removeClass().addClass(`fas fa-${icon} text-${getColorByType(type)}`);

                // Set button color based on type
                const $confirmBtn = $('#confirmActionBtn');
                $confirmBtn.removeClass('btn-primary btn-danger btn-warning btn-info btn-success')
                    .addClass(`btn-${getButtonClassByType(type)}`);

                // Set gradient for primary buttons
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

                // Show modal
                const confirmModal = new bootstrap.Modal(document.getElementById('elegantConfirmModal'));
                confirmModal.show();

                // Handle confirm action
                $('#confirmActionBtn').off('click').on('click', function() {
                    confirmModal.hide();
                    resolve(true);
                });

                // Handle cancel action
                $('#elegantConfirmModal').off('hidden.bs.modal').on('hidden.bs.modal', function() {
                    resolve(false);
                });
            });
        }

// Helper functions
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

// Fungsi untuk show success message
        function showSuccessMessage(message, duration = 2000) {
            $('#successMessage').text(message);
            const successModal = new bootstrap.Modal(document.getElementById('successToastModal'));
            successModal.show();

            // Auto hide after duration
            setTimeout(() => {
                successModal.hide();
            }, duration);
        }

        // Run initialization
        initializePage();
    });
</script>
</body>
</html>
