<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Content Security Policy -->
    <meta http-equiv="Content-Security-Policy"
        content="
        default-src 'self';
        script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;
        style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;
        font-src 'self' https://cdn.jsdelivr.net;
        img-src 'self' data: https:;
        connect-src 'self';
    ">

    <!-- SEO Meta Tags -->
    <meta name="description" content="SEDAP - Sistem Deteksi Angka Kemiskinan">
    <meta name="author" content="BPS Kabupaten Bantul">

    <!-- Title -->
    <title>@yield('title', 'Dashboard') - SEDAP</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha512-b2QcS5SsA8tZodcDtGRELiGv5SaKSk1vDHDaQRda0htPYWZ6046lr3kJ5bAAQdpV2mmA/4v0wQF9MyU6/pDIAg=="
        crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.2/font/bootstrap-icons.min.css"
        rel="stylesheet"
        integrity="sha512-D1liES3uvDpPrgk7vXR/hR/sukGn7EtDWEyvpdLsyalQYq6v6YUsTUJmku7B4rcuQ11hMJVJl2OUhduGTNqYOQ=="
        crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" <!-- Additional Styles -->
    @yield('styles')

    <!-- Main Layout Styles -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --header-height: 60px;
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

        .sidebar-container.collapsed {
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

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-1px);
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
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Sidebar Mobile Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <!-- Main App Container -->
    <div class="app-container">
        <!-- Sidebar -->
        <div id="sidebar-container" class="sidebar-container">
            @include('layouts.sidebar')
        </div>

        <!-- Main Content Wrapper -->
        <div class="main-wrapper">
            <!-- Header -->
            <header class="main-header">
                <div class="header-left">
                    <button id="sidebar-toggle" class="btn btn-link d-md-none p-0 me-3">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="header-actions">
                    <!-- Breadcrumb -->
                    @hasSection('breadcrumb')
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-nav">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    @endif

                    <!-- User Info -->
                    <div class="user-info d-none d-sm-flex">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Content Header -->
                @hasSection('content-header')
                    <div class="content-header">
                        @yield('content-header')
                    </div>
                @endif

                <!-- Flash Messages -->
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

                <!-- Validation Errors -->
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
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Custom JavaScript - CSP Safe Version -->
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

                function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
                    return confirm(message);
                }
    </script>

    <!-- Additional Scripts -->
    @yield('scripts')
</body>

</html>
