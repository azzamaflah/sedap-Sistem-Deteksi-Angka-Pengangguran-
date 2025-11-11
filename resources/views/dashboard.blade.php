@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('styles')
    {{-- (STYLE ASLI ANDA) --}}
    <style>
        /* ===== WELCOME CARD GRADIENT ===== */
        .welcome-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
            min-height: 200px;
        }

        .welcome-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .welcome-gradient::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        .welcome-badge {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            animation: slideInDown 0.6s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== STATS CARD ENHANCEMENTS ===== */
        .stats-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }

        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15) !important;
            border-color: currentColor;
        }

        .stats-card:hover::before {
            left: 100%;
        }

        /* Icon Box with Pulse Animation */
        .icon-box {
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            flex-shrink: 0;
            border-radius: 15px;
            position: relative;
            transition: all 0.3s ease;
        }

        .stats-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
            animation: iconPulse 1s ease-in-out infinite;
        }

        @keyframes iconPulse {

            0%,
            100% {
                transform: scale(1.1) rotate(5deg);
            }

            50% {
                transform: scale(1.2) rotate(-5deg);
            }
        }

        /* Number Animation */
        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, currentColor, currentColor);
            -webkit-background-clip: text;
            background-clip: text;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Detail Link Hover Effect */
        .detail-link {
            position: relative;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .detail-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: currentColor;
            transition: width 0.3s ease;
        }

        .detail-link:hover::after {
            width: 100%;
        }

        .detail-link:hover i {
            transform: translateX(5px);
            transition: transform 0.3s ease;
        }

        /* ===== QUICK ACTION BUTTONS ===== */
        .quick-action-btn {
            border-width: 2px;
            border-style: dashed;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: white;
        }

        .quick-action-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.1;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .quick-action-btn:hover {
            border-style: solid;
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .quick-action-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .quick-action-btn i {
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover i {
            transform: scale(1.2) rotate(10deg);
        }

        /* ===== ICON CIRCLE ===== */
        .icon-circle {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.4rem;
            transition: all 0.3s ease;
        }

        .card:hover .icon-circle {
            transform: rotate(360deg) scale(1.1);
        }

        /* Icon Small for System Info */
        .icon-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .list-unstyled li:hover .icon-sm {
            transform: scale(1.2) rotate(10deg);
        }

        /* ===== CARD HEADER ENHANCEMENTS ===== */
        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 2px solid #f0f0f0 !important;
        }

        /* ===== ACTIVITY STATS ICON ANIMATION ===== */
        .activity-icon {
            transition: all 0.3s ease;
            display: inline-block;
        }

        .activity-icon:hover {
            transform: scale(1.3) rotate(15deg);
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        /* ===== SYSTEM INFO LIST HOVER ===== */
        .system-info-item {
            transition: all 0.3s ease;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .system-info-item:hover {
            background: rgba(0, 0, 0, 0.02);
            transform: translateX(5px);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .icon-box {
                width: 55px;
                height: 55px;
                font-size: 1.5rem;
            }

            .stats-number {
                font-size: 2rem;
            }

            .welcome-gradient {
                min-height: 150px;
            }
        }

        /* ===== SMOOTH PAGE LOAD ANIMATION ===== */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stagger animation for cards */
        .stats-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stats-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stats-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stats-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        /* ===== CSS BARU UNTUK FILTER ===== */
        .form-filter-select {
            border-radius: 50px !important;
            height: 45px !important;
            border: 2px solid #e0e0e0 !important;
            transition: all 0.3s ease !important;
            font-weight: 500;
        }
        .form-filter-select:focus {
            border-color: #007bff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15) !important;
            outline: none !important;
        }
    </style>
@endsection

@section('content')
    {{-- ====== WELCOME CARD ====== --}}
    <div class="row g-2 mb-2 fade-in">
        <div class="col-12">
            <div class="card border-0 shadow-sm welcome-gradient overflow-hidden">
                <div class="card-body p-4 text-white position-relative" style="z-index: 10;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <span class="welcome-badge">
                                    <i class="bi bi-shield-check"></i>
                                    {{ Auth::user()->role ?? 'Administrator' }}
                                </span>
                            </div>
                            <h3 class="mb-2 fw-bold" style="font-size: 2rem;">
                                Selamat Datang, {{ Auth::user()->nama ?? (Auth::user()->name ?? 'Admin') }}! 👋
                            </h3>
                            <p class="mb-3 opacity-90" style="font-size: 1.1rem;">
                                Sistem Deteksi Angka Pengangguran - BPS Kabupaten Bantul
                            </p>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <div class="d-flex align-items-center" style="font-size: 0.95rem;">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    <span id="realtime-date">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
                                </div>
                                <div class="d-flex align-items-center" style="font-size: 0.95rem;">
                                    <i class="bi bi-clock-fill me-2"></i>
                                    <span id="realtime-clock">{{ now()->format('H:i:s') }}</span> WIB
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end d-none d-md-block">
                            <i class="bi bi-bar-chart-fill"
                                style="font-size: 9rem; opacity: 0.2; animation: float 4s ease-in-out infinite;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== BARIS FILTER BARU ====== --}}
    <div class="row g-3 mb-3 fade-in" style="animation-delay: 0.05s;">
        <div class="col-12">
            <form id="filterForm" method="GET" action="{{ route('dashboard') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label class="form-label fw-bold mb-0">
                            <i class="bi bi-funnel-fill"></i> Tampilkan Data:
                        </label>
                    </div>
                    {{-- Dropdown Tahun --}}
                    <div class="col-12 col-sm-auto col-md-3 col-lg-2">
                        <select name="year" class="form-select form-filter-select">
                            {{-- Tampilkan tahun yang tersedia --}}
                            @forelse($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    Tahun {{ $year }}
                                </option>
                            @empty
                                {{-- Fallback jika tidak ada data sama sekali --}}
                                <option value="{{ date('Y') }}" selected>Tahun {{ date('Y') }}</option>
                            @endforelse
                        </select>
                    </div>
                    {{-- Dropdown Semester --}}
                    <div class="col-12 col-sm-auto col-md-3 col-lg-2">
                        <select name="semester" class="form-select form-filter-select">
                            <option value="">Semua Semester</option>
                            <option value="1" {{ $selectedSemester == 1 ? 'selected' : '' }}>Semester 1 (Jan-Jun)</option>
                            <option value="2" {{ $selectedSemester == 2 ? 'selected' : '' }}>Semester 2 (Jul-Des)</option>
                        </select>
                    </div>
                    {{-- Tombol Reset (Hanya muncul jika ada filter non-default) --}}
                    @if(request('year') || request('semester'))
                    <div class="col-auto">
                         <a href="{{ route('dashboard') }}" class="btn btn-outline-danger" 
                            style="border-radius: 50px; height: 45px; width: 45px; display: flex; align-items: center; justify-content: center;" 
                            data-bs-toggle="tooltip" title="Reset Filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>


    {{-- ====== STATS CARD ROW (Data sudah difilter di Controller) ====== --}}
    <div class="row g-3 mb-4">
        {{-- Card 1: Wilayah Tugas --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card fade-in"
                onclick="window.location='{{ route('wilayahTugas.index') }}'">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase fw-semibold"
                                style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                Wilayah Tugas
                            </p>
                            <h2 class="mb-0 stats-number text-primary" style="font-size: 2.8rem;">
                                {{ number_format($totalWilayahTugas ?? 0) }}</h2>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary"
                            style="width: 70px; height: 70px; border-radius: 18px;">
                            <i class="bi bi-geo-alt-fill" style="font-size: 2.2rem;"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <small class="text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-pin-map"></i> Total Blok Sensus
                        </small>
                        <a href="{{ route('wilayahTugas.index') }}"
                            class="text-decoration-none text-primary fw-semibold small detail-link"
                            style="font-size: 0.85rem;">
                            Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Sampel RT (DSRT) --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card fade-in"
                onclick="window.location='{{ route('dsrt.index') }}'">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase fw-semibold"
                                style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                Sampel RT (DSRT)
                            </p>
                            <h2 class="mb-0 stats-number text-success" style="font-size: 2.8rem;">
                                {{ number_format($totalDsrt ?? 0) }}</h2>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success"
                            style="width: 70px; height: 70px; border-radius: 18px;">
                            <i class="bi bi-file-earmark-text-fill" style="font-size: 2.2rem;"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <small class="text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-house-door"></i> Total Rumah Tangga
                        </small>
                        <a href="{{ route('dsrt.index') }}"
                            class="text-decoration-none text-success fw-semibold small detail-link"
                            style="font-size: 0.85rem;">
                            Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Total Responden --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card fade-in"
                onclick="window.location='{{ route('responden.index') }}'">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase fw-semibold"
                                style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                Total Responden
                            </p>
                            <h2 class="mb-0 stats-number text-warning" style="font-size: 2.8rem;">
                                {{ number_format($totalResponden ?? 0) }}</h2>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning"
                            style="width: 70px; height: 70px; border-radius: 18px;">
                            <i class="bi bi-people-fill" style="font-size: 2.2rem;"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <small class="text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-person-check"></i> Data Terkumpul
                        </small>
                        <a href="{{ route('responden.index') }}"
                            class="text-decoration-none text-warning fw-semibold small detail-link"
                            style="font-size: 0.85rem;">
                            Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Aksi Cepat (DIKEMBALIKAN SESUAI PERMINTAAN) --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card fade-in">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase fw-semibold"
                                style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                Aksi Cepat
                            </p>
                        </div>
                    </div>

                    {{-- Bagian 2: Tombol Aksi (Body Konten Utama) --}}
                    <div class="row g-2 text-center mb-3">
                        {{-- Tombol 1: Tambah Wilayah (col-4) --}}
                        <div class="col-4">
                            <a href="{{ route('wilayahTugas.create') }}"
                                class="btn btn-outline-primary btn-sm quick-action-btn w-100 p-2"
                                title="Tambah Wilayah">
                                <i class="bi bi-geo-alt-fill d-block mb-1" style="font-size: 1.1rem;"></i>
                                <span style="font-size: 0.65rem; font-weight: 600;">Wilayah</span>
                            </a>
                        </div>

                        {{-- Tombol 2: Tambah Sampel RT (col-4) --}}
                        <div class="col-4">
                            <a href="{{ route('dsrt.create') }}"
                                class="btn btn-outline-success btn-sm quick-action-btn w-100 p-2"
                                title="Tambah Sampel RT">
                                <i class="bi bi-file-earmark-text-fill d-block mb-1" style="font-size: 1.1rem;"></i>
                                <span style="font-size: 0.65rem; font-weight: 600;">Sampel RT</span>
                            </a>
                        </div>

                        {{-- Tombol 3: Tambah Responden (col-4) --}}
                        <div class="col-4">
                            <a href="{{ route('responden.create') }}"
                                class="btn btn-outline-warning btn-sm quick-action-btn w-100 p-2"
                                title="Tambah Responden">
                                <i class="bi bi-person-plus-fill d-block mb-1" style="font-size: 1.1rem;"></i>
                                <span style="font-size: 0.65rem; font-weight: 600;">Responden</span>
                            </a>
                        </div>
                    </div>

                    <small class="text-muted" style="font-size: 0.8rem;">

                        <i class="bi bi-plus-circle"></i> tambah data

                    </small>

                </div>

            </div>

        </div>

    </div>

    {{-- ====== CHART ROW 1 ====== --}}
    <div class="row g-3 mb-4">
        {{-- GRAFIK 1: Status Ketenagakerjaan (Pie) --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100 fade-in" style="animation-delay: 0.5s;">
                <div class="card-header">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Status Ketenagakerjaan
                    </h5>
                    {{-- GANTI TAHUN DENGAN FILTER DISPLAY --}}
                    <small class="text-muted">Proporsi Bekerja vs Pengangguran ({{ $filterDisplay }})</small>
                </div>
                <div class="card-body p-3 d-flex align-items-center justify-content-center">
                    <div style="min-height: 350px; width: 100%;">
                        <canvas id="statusPekerjaanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRAFIK 4: Sebaran Pengangguran (Bar) --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100 fade-in" style="animation-delay: 0.6s;">
                <div class="card-header">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-bar-chart-line-fill me-2 text-danger"></i>Sebaran Pengangguran per Kecamatan
                    </h5>
                    {{-- GANTI TAHUN DENGAN FILTER DISPLAY --}}
                    <small class="text-muted">Jumlah Responden Pengangguran di Tiap Wilayah ({{ $filterDisplay }})</small>
                </div>
                <div class="card-body p-3">
                    <div style="min-height: 350px; width: 100%;">
                        <canvas id="sebaranPengangguranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== CHART ROW 2 ====== --}}
    <div class="row g-3 mb-4">
        {{-- GRAFIK 3: Progress Entri Responden by Pengawas (Bar) --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 fade-in" style="animation-delay: 0.7s;">
                <div class="card-header">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-person-check-fill me-2 text-success"></i>Top 10 Progress Pengawas
                    </h5>
                    {{-- GANTI TAHUN DENGAN FILTER DISPLAY --}}
                    <small class="text-muted">Jumlah Entri Responden per Pengawas ({{ $filterDisplay }})</small>
                </div>
                <div class="card-body p-3">
                    <div style="min-height: 350px; width: 100%;">
                        <canvas id="progressPengawasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRAFIK 2: Progress Entri DSRT by NKS (Bar) --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 fade-in" style="animation-delay: 0.8s;">
                <div class="card-header">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-bar-chart-steps me-2 text-info"></i>Top 10 Progress DSRT per NKS
                    </h5>
                    {{-- GANTI TAHUN DENGAN FILTER DISPLAY --}}
                    <small class="text-muted">Jumlah Entri Rumah Tangga per NKS ({{ $filterDisplay }})</small>
                </div>
                <div class="card-body p-3">
                    <div style="min-height: 350px; width: 100%;">
                        <canvas id="progressNKSChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Script Asli Anda (Jam, Animasi, dll) --}}
    <script>
        // Fungsi untuk memperbarui jam secara real-time
        function updateClock() {
            const clockElement = document.getElementById('realtime-clock');
            const dateElement = document.getElementById('realtime-date');

            if (clockElement) {
                const now = new Date();
                const timeOptions = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };
                const dateOptions = {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                };
                const timeString = now.toLocaleTimeString('id-ID', timeOptions);
                const dateString = now.toLocaleDateString('id-ID', dateOptions);
                clockElement.textContent = timeString;
                if (dateElement && dateElement.textContent !== dateString) {
                    dateElement.textContent = dateString;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // >>> Real-time Clock Initialization <<<
            updateClock();
            setInterval(updateClock, 1000);

            // Bootstrap Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Stats cards click handler
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    // Hanya redirect jika card *tidak* berisi tombol aksi cepat
                    if (!this.querySelector('.quick-action-btn')) {
                        const link = this.querySelector('a[href]');
                        if (link && !e.target.closest('a')) {
                            // Cek jika rute ada sebelum redirect
                            if (link.getAttribute('href') && link.getAttribute('href') !== '#') {
                                window.location.href = link.getAttribute('href');
                            }
                        }
                    }
                });
            });

            // Prevent double click on detail links
            document.querySelectorAll('.detail-link, .quick-action-btn').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.stopPropagation(); // Mencegah card di-klik saat tombol di-klik
                });
            });

             // ===== SCRIPT BARU UNTUK AUTO-SUBMIT FILTER DROPDOWN =====
            const filterForm = document.getElementById('filterForm');
            const yearSelect = document.querySelector('select[name="year"]');
            const semesterSelect = document.querySelector('select[name="semester"]');
    
            function submitForm() {
                // Tampilkan overlay loading sederhana
                let overlay = document.createElement('div');
                overlay.style = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 9998; display: flex; align-items: center; justify-content: center;';
                overlay.innerHTML = '<div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"><span class="visually-hidden">Loading...</span></div>';
                document.body.appendChild(overlay);
                
                filterForm.submit();
            }
    
            if (yearSelect) {
                yearSelect.addEventListener('change', submitForm);
            }
            if (semesterSelect) {
                semesterSelect.addEventListener('change', submitForm);
            }
            // =========================================================
        });
    </script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Script Chart (Diambil dari kode asli Anda) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Helper function untuk menampilkan pesan jika data kosong
            const showEmptyChartMessage = (canvasId, message) => {
                const canvas = document.getElementById(canvasId);
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height); // Hapus chart lama jika ada
                    canvas.style.display = 'none'; // Sembunyikan canvas
                    
                    const parent = canvas.parentElement;
                    if(parent) {
                        let msgDiv = parent.querySelector('.empty-chart-msg');
                        if (!msgDiv) {
                            msgDiv = document.createElement('div');
                            msgDiv.className = 'empty-chart-msg alert alert-warning text-center d-flex align-items-center justify-content-center';
                            msgDiv.style.minHeight = '350px';
                            parent.appendChild(msgDiv);
                        }
                        msgDiv.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i> ${message}`;
                    }
                }
            };

            // Ambil data dari Controller
            try { 
                const chartStatusPekerjaan = {!! json_encode($chartStatusPekerjaan) !!};
                const chartNKS = {!! json_encode($chartNKS) !!};
                const chartPengawas = {!! json_encode($chartPengawas) !!};
                const chartSebaran = {!! json_encode($chartSebaranPengangguran) !!};
                const filterDisplay = '{{ $filterDisplay }}'; // Judul filter

                // ===== 1. Chart Status Ketenagakerjaan (Doughnut) =====
                const ctxStatus = document.getElementById('statusPekerjaanChart');
                if (ctxStatus && chartStatusPekerjaan.data && chartStatusPekerjaan.data.some(d => d > 0)) {
                    new Chart(ctxStatus.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: chartStatusPekerjaan.labels,
                            datasets: [{
                                data: chartStatusPekerjaan.data,
                                backgroundColor: chartStatusPekerjaan.colors,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                title: {
                                    display: false,
                                }
                            }
                        }
                    });
                } else if(ctxStatus) {
                    showEmptyChartMessage('statusPekerjaanChart', `Data Status Ketenagakerjaan (${filterDisplay}) belum tersedia.`);
                }

                // ===== 2. Chart Sebaran Pengangguran (Horizontal Bar) =====
                const ctxSebaran = document.getElementById('sebaranPengangguranChart');
                if (ctxSebaran && chartSebaran.data && chartSebaran.data.length > 0) {
                    new Chart(ctxSebaran.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: chartSebaran.labels,
                            datasets: [{
                                label: 'Jumlah Pengangguran',
                                data: chartSebaran.data,
                                backgroundColor: '#dc3545', // Warna merah untuk pengangguran
                                borderColor: '#dc3545',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y', // Membuat bar menjadi horizontal
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false 
                                },
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                } else if(ctxSebaran) {
                     showEmptyChartMessage('sebaranPengangguranChart', `Data Sebaran Pengangguran (${filterDisplay}) belum tersedia.`);
                }

                // ===== 3. Chart Progress Pengawas (Horizontal Bar) =====
                const ctxPengawas = document.getElementById('progressPengawasChart');
                if (ctxPengawas && chartPengawas.data && chartPengawas.data.length > 0) {
                    new Chart(ctxPengawas.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: chartPengawas.labels,
                            datasets: [{
                                label: 'Total Responden',
                                data: chartPengawas.data,
                                backgroundColor: chartPengawas.backgroundColor,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y', // Bar horizontal
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                } else if(ctxPengawas) {
                    showEmptyChartMessage('progressPengawasChart', `Data Progress Pengawas (${filterDisplay}) belum tersedia.`);
                }

                // ===== 4. Chart Progress NKS (Vertical Bar) =====
                const ctxNKS = document.getElementById('progressNKSChart');
                if (ctxNKS && chartNKS.data && chartNKS.data.length > 0) {
                    new Chart(ctxNKS.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: chartNKS.labels,
                            datasets: [{
                                label: 'Total Rumah Tangga',
                                data: chartNKS.data,
                                backgroundColor: chartNKS.backgroundColor,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'x', // Bar vertikal
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                } else if(ctxNKS) {
                    showEmptyChartMessage('progressNKSChart', `Data Progress NKS (${filterDisplay}) belum tersedia.`);
                }

            } catch (e) {
                console.error("Gagal memuat data chart:", e);
                // Tampilkan pesan error jika JSON atau rendering gagal
                showEmptyChartMessage('statusPekerjaanChart', 'Gagal memuat chart.');
                showEmptyChartMessage('sebaranPengangguranChart', 'Gagal memuat chart.');
                showEmptyChartMessage('progressPengawasChart', 'Gagal memuat chart.');
                showEmptyChartMessage('progressNKSChart', 'Gagal memuat chart.');
            }
        });
    </script>
@endsection