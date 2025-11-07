<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('logov.png') }}" type="image/x-icon">

    <meta http-equiv="Content-Security-Policy"
        content="
        default-src 'self';
        script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;
        style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;
        font-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;
        img-src 'self' data: https:;
        connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;
    ">

    <meta name="description" content="SEDAP - Sistem Deteksi Angka Kemiskinan">
    <meta name="author" content="BPS Kabupaten Bantul">

    <title>@yield('title', 'Dashboard') - SEDAP</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha512-b2QcS5SsA8tZodcDtGRELiGv5SaKSk1vDHDaQRda0htPYWZ6046lr3kJ5bAAQdpV2mmA/4v0wQF9MyU6/pDIAg=="
        crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">


    @yield('styles')

    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 75px;

            /* Warna Tema Terang */
            --sidebar-bg: #ffffff;
            --sidebar-text: #334155;
            /* Teks gelap */
            --sidebar-text-muted: #64748b;
            --sidebar-hover: #f1f5f9;
            /* Hover abu-abu muda */
            --sidebar-active: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            /* Active tetap ungu */
            --sidebar-border: #e2e8f0;
            /* Border abu-abu */
            --sidebar-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --header-height: 80px;

            --accent-primary: #6366f1;
            --accent-secondary: #8b5cf6;
            --transition-speed: 0.3s;
            --transition-timing: cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Styles */
        .sidebar-container {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            background-color: #fff;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1000;
        }

        .sidebar-container:has(> #mainSidebar.collapsed) {
            width: var(--sidebar-collapsed-width);
            min-width: var(--sidebar-collapsed-width);
        }

        /* Main Content Area */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f7fa;
        }

        /* Header */
        .main-header {
            background: #fff;
            height: var(--header-height);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #e9ecef;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .main-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-x: auto;
        }

        /* Content Header */
        .content-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .breadcrumb-nav {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }

        .breadcrumb-nav .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb-nav .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            border-radius: 10px 10px 0 0 !important;
            padding: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
            border-top: none;
            padding: 1rem 0.75rem;
        }

        .table td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Loading */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar-container {
                position: fixed;
                left: -280px;
                height: 100vh;
                z-index: 1050;
            }

            .sidebar-container.show {
                left: 0;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .main-content {
                padding: 1rem;
            }

            .content-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            .main-header {
                padding: 0 1rem;
            }

            .main-header h1 {
                font-size: 1.2rem;
            }
        }

        /* Sidebar Mobile Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar-overlay.show {
                display: block;
            }
        }

        .sidebar::before {
            /* Hapus efek gradient ungu di atas */
            display: none;
        }

        .brand-title {
            /* Ubah warna teks brand jadi gelap */
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-subtitle {
            color: var(--sidebar-text-muted);
        }

        .sidebar-toggle-btn {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #475569;
        }

        .sidebar-toggle-btn:hover {
            background: #e2e8f0;
        }

        .menu-item.active .menu-link {
            color: white;
            /* Teks di tombol aktif tetap putih */
        }

        .user-profile-btn {
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        .user-profile-btn:hover {
            background: #f1f5f9;
        }

        .user-name {
            color: #1e293b;
        }

        .profile-arrow {
            color: var(--sidebar-text);
        }
    </style>
</head>

<body>
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <div class="app-container">
        <div id="sidebar-container" class="sidebar-container">
            @include('layouts.sidebar')
        </div>

        <div class="main-wrapper">
            <header class="main-header">
                <div class="header-left">
                    <button id="sidebar-toggle" class="btn btn-link d-md-none p-0 me-3">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="header-actions">
                    @hasSection('breadcrumb')
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-nav">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    @endif
                </div>
            </header>

            <main class="main-content">
                <!-- @hasSection('content-header')
                    <div class="content-header">
                        @yield('content-header')
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif -->

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        (function() {
            'use strict';

            // Global App Object - CSP Safe
            window.SEDAP = {
                baseUrl: '{{ url('/') }}',
                csrfToken: '{{ csrf_token() }}',
                user: {!! json_encode(Auth::user() ?? null) !!}
            };
            // DOM Ready
            document.addEventListener('DOMContentLoaded', function() {
                // Sidebar Toggle for Mobile
                const sidebarToggle = document.getElementById('sidebar-toggle');
                const sidebarContainer = document.getElementById('sidebar-container');
                const sidebarOverlay = document.getElementById('sidebar-overlay');

                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', function() {
                        sidebarContainer.classList.toggle('show');
                        sidebarOverlay.classList.toggle('show');
                    });
                }

                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', function() {
                        sidebarContainer.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    });
                }

                // Auto hide alerts after 5 seconds
                const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
                alerts.forEach(function(alert) {
                    setTimeout(function() {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }, 5000);
                });

                // Loading overlay functions
                window.showLoading = function() {
                    document.getElementById('loading-overlay').style.display = 'flex';
                };

                window.hideLoading = function() {
                    document.getElementById('loading-overlay').style.display = 'none';
                };

                // Form submission loading
                document.querySelectorAll('form').forEach(function(form) {
                    form.addEventListener('submit', function() {
                        showLoading();
                    });
                });

                // AJAX Setup
                if (typeof axios !== 'undefined') {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = SEDAP.csrfToken;
                    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                }

                // jQuery AJAX Setup (if jQuery is loaded)
                if (typeof $ !== 'undefined') {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': SEDAP.csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                }
            });

            // Utility Functions
            function showAlert(message, type = 'success') {
                const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

                const mainContent = document.querySelector('.main-content');
                mainContent.insertAdjacentHTML('afterbegin', alertHtml);

                // Auto hide after 5 seconds
                setTimeout(function() {
                    const alert = mainContent.querySelector('.alert');
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            }

            window.confirmDelete = function(message = 'Apakah Anda yakin ingin menghapus data ini?') {
                return confirm(message);
            }

            // Memastikan fungsi showAlert dan confirmDelete tersedia secara global
            window.showAlert = showAlert;

        })();
    </script>

    @yield('scripts')
</body>

</html>