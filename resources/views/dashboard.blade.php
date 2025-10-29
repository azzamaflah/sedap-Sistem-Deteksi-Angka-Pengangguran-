@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <!-- Welcome Card -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient overflow-hidden"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 180px;">
                <div class="card-body p-4 text-white position-relative">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="mb-1">
                                <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 mb-3">
                                    <i class="bi bi-shield-check"></i> {{ Auth::user()->role ?? 'Administrator' }}
                                </span>
                            </div>
                            <h3 class="mb-2 fw-bold">Selamat Datang,
                                {{ Auth::user()->nama ?? (Auth::user()->name ?? 'Admin') }}! 👋</h3>
                            <p class="mb-3 opacity-90" style="font-size: 1.05rem;">
                                Sistem Deteksi Angka Kemiskinan - BPS Kabupaten Bantul
                            </p>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <p class="mb-0" style="font-size: 0.95rem;">
                                    <i class="bi bi-calendar3 me-2"></i>{{ now()->isoFormat('dddd, D MMMM Y') }}
                                </p>
                                <p class="mb-0" style="font-size: 0.95rem;">
                                    <i class="bi bi-clock-fill me-2"></i>{{ now()->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-end d-none d-md-block">
                            <i class="bi bi-bar-chart-fill" style="font-size: 8rem; opacity: 0.15;"></i>
                        </div>
                    </div>
                    <!-- Decorative Elements -->
                    <div class="position-absolute top-0 end-0 translate-middle-y"
                        style="width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; margin-top: -50px; margin-right: -50px;">
                    </div>
                    <div class="position-absolute bottom-0 start-0"
                        style="width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; margin-bottom: -75px; margin-left: -75px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Wilayah Tugas -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase fw-semibold"
                                style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                Wilayah Tugas
                            </p>
                            <h2 class="mb-0 fw-bold text-primary">{{ number_format($totalWilayahTugas ?? 0) }}</h2>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <small class="text-muted">
                            <i class="bi bi-clock-history"></i> Update terbaru
                        </small>
                        <a href="{{ route('wilayahTugas.index') }}"
                            class="text-decoration-none text-primary fw-semibold small">
                            Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: DSRT -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase fw-semibold"
                                style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                Sampel RT (DSRT)
                            </p>
                            <h2 class="mb-0 fw-bold text-success">{{ number_format($totalDsrt ?? 0) }}</h2>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <small class="text-muted">
                            <i class="bi bi-graph-up"></i> Total sampel
                        </small>
                        <a href="{{ route('dsrt.index') }}" class="text-decoration-none text-success fw-semibold small">
                            Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Responden -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase fw-semibold"
                                style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                Total Responden
                            </p>
                            <h2 class="mb-0 fw-bold text-warning">{{ number_format($totalResponden ?? 0) }}</h2>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <small class="text-muted">
                            <i class="bi bi-person-check"></i> Data terkumpul
                        </small>
                        <a href="{{ route('responden.index') }}"
                            class="text-decoration-none text-warning fw-semibold small">
                            Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Pengguna -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase fw-semibold"
                                style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                Pengguna Sistem
                            </p>
                            <h2 class="mb-0 fw-bold text-info">{{ number_format($totalPengguna ?? 0) }}</h2>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-3">
                            <i class="bi bi-person-fill-gear"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <small class="text-muted">
                            <i class="bi bi-shield-check"></i> Akun aktif
                        </small>
                        <a href="{{ route('pengguna.index') }}" class="text-decoration-none text-info fw-semibold small">
                            Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-warning bg-opacity-10 text-warning me-3">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Aksi Cepat</h5>
                            <small class="text-muted">Tambahkan data baru dengan cepat</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('wilayahTugas.create') }}"
                                class="btn btn-outline-primary w-100 py-3 quick-action-btn">
                                <i class="bi bi-geo-alt-fill fs-3 d-block mb-2"></i>
                                <span class="fw-semibold d-block">Wilayah Tugas</span>
                                <small class="text-muted">Tambah baru</small>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('dsrt.create') }}"
                                class="btn btn-outline-success w-100 py-3 quick-action-btn">
                                <i class="bi bi-file-earmark-text-fill fs-3 d-block mb-2"></i>
                                <span class="fw-semibold d-block">Sampel RT</span>
                                <small class="text-muted">Tambah baru</small>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('responden.create') }}"
                                class="btn btn-outline-warning w-100 py-3 quick-action-btn">
                                <i class="bi bi-person-plus-fill fs-3 d-block mb-2"></i>
                                <span class="fw-semibold d-block">Responden</span>
                                <small class="text-muted">Tambah baru</small>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('pengguna.create') }}"
                                class="btn btn-outline-info w-100 py-3 quick-action-btn">
                                <i class="bi bi-person-fill-add fs-3 d-block mb-2"></i>
                                <span class="fw-semibold d-block">Pengguna</span>
                                <small class="text-muted">Tambah baru</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-info bg-opacity-10 text-info me-3">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Informasi Sistem</h5>
                            <small class="text-muted">Status & aktivitas</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex align-items-center">
                            <div class="icon-sm bg-success bg-opacity-10 text-success rounded-circle me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Sistem Aktif</div>
                                <small class="text-muted">Berjalan normal</small>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <div class="icon-sm bg-primary bg-opacity-10 text-primary rounded-circle me-3">
                                <i class="bi bi-database-fill"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Database</div>
                                <small class="text-muted">Terkoneksi</small>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <div class="icon-sm bg-warning bg-opacity-10 text-warning rounded-circle me-3">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Pengguna Aktif</div>
                                <small class="text-muted">{{ Auth::user()->name ?? 'Administrator' }}</small>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <div class="icon-sm bg-info bg-opacity-10 text-info rounded-circle me-3">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Versi Sistem</div>
                                <small class="text-muted">SEDAP v1.0.0</small>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row (Optional) -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-graph-up text-success me-2"></i>
                        Ringkasan Aktivitas
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="bi bi-clipboard-data text-primary fs-1 mb-2 d-block"></i>
                                <h4 class="fw-bold mb-1">{{ $totalWilayahTugas ?? 0 }}</h4>
                                <small class="text-muted">Total Wilayah</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="bi bi-file-earmark-check text-success fs-1 mb-2 d-block"></i>
                                <h4 class="fw-bold mb-1">{{ $totalDsrt ?? 0 }}</h4>
                                <small class="text-muted">Sampel Terdaftar</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="bi bi-people text-warning fs-1 mb-2 d-block"></i>
                                <h4 class="fw-bold mb-1">{{ $totalResponden ?? 0 }}</h4>
                                <small class="text-muted">Data Responden</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="bi bi-person-badge text-info fs-1 mb-2 d-block"></i>
                                <h4 class="fw-bold mb-1">{{ $totalPengguna ?? 0 }}</h4>
                                <small class="text-muted">Pengguna Aktif</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Welcome Card Enhancements */
        .bg-gradient {
            position: relative;
            overflow: hidden;
        }

        /* Stats Card Hover Effect */
        .stats-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }

        /* Icon Box */
        .icon-box {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        /* Icon Circle */
        .icon-circle {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1.25rem;
        }

        /* Icon Small */
        .icon-sm {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        /* Quick Action Button */
        .quick-action-btn {
            border-width: 2px;
            border-style: dashed;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .quick-action-btn:hover {
            border-style: solid;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .quick-action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .quick-action-btn:hover::before {
            left: 100%;
        }

        /* Number Animation */
        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stats-card h2 {
            animation: countUp 0.6s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .icon-box {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .stats-card h2 {
                font-size: 1.75rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Animate numbers on page load
        document.addEventListener('DOMContentLoaded', function() {
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
            });

            // Add click event to stats cards
            statsCards.forEach(card => {
                card.addEventListener('click', function() {
                    const link = this.querySelector('a[href]');
                    if (link) {
                        window.location.href = link.getAttribute('href');
                    }
                });
            });
        });
    </script>
@endsection
