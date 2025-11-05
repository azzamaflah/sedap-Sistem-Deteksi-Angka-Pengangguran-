@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('styles')
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
    </style>
@endsection

@section('content')
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
                        <i class="bi bi-clock-history"></i> Update terbaru
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
                        <i class="bi bi-graph-up"></i> Total sampel
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
                        <i class="bi bi-person-check"></i> Data terkumpul
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

    {{-- Card 4: Aksi Cepat (Grid 3 Kolom Horizontal) - Disesuaikan dengan Pola Card 3 --}}
<div class="col-xl-3 col-md-6"> 
    <div class="card border-0 shadow-sm h-100 stats-card fade-in">
        <div class="card-body p-4">
            {{-- Bagian 1: Header Ringkas (Mengganti card-header lama) --}}
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
            
            {{-- Bagian 3: Footer Rinci (Dibuat konsisten dengan Card 3) --}}
            <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                <small class="text-muted" style="font-size: 0.8rem;">
                    <i class="bi bi-plus-circle"></i> tambah data
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        // Fungsi untuk memperbarui jam secara real-time
        function updateClock() {
            const clockElement = document.getElementById('realtime-clock');
            const dateElement = document.getElementById('realtime-date');

            if (clockElement) {
                // Gunakan waktu lokal browser untuk real-time, ini akan lebih akurat di sisi klien
                const now = new Date();

                // Opsi untuk format jam 24 jam (HH:MM:SS)
                const timeOptions = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };

                // Opsi untuk format tanggal (dddd, D MMMM Y) - menyesuaikan dengan PHP isoFormat
                const dateOptions = {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                };

                // Format waktu ke H:i:s dan tanggal ke dddd, D MMMM Y (menggunakan locale ID untuk bahasa Indonesia)
                const timeString = now.toLocaleTimeString('id-ID', timeOptions);
                const dateString = now.toLocaleDateString('id-ID', dateOptions);

                // Update jam
                clockElement.textContent = timeString;

                // Update tanggal (hanya perbarui jika tanggal berubah)
                if (dateElement && dateElement.textContent !== dateString) {
                    dateElement.textContent = dateString;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // >>> Real-time Clock Initialization <<<
            updateClock();
            // Perbarui jam setiap 1 detik
            setInterval(updateClock, 1000);

            // Smooth scroll reveal animation
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target); // Stop observing once visible
                    }
                });
            }, {
                threshold: 0.1
            });


            // Stats cards click handler
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    const link = this.querySelector('a[href]');
                    if (link && !e.target.closest('a')) {
                        window.location.href = link.getAttribute('href');
                    }
                });
            });

            // Prevent double click on detail links
            document.querySelectorAll('.detail-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
@endsection
