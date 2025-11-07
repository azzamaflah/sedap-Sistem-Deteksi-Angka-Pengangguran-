@extends('layouts.app')

@section('title', 'Kelola Rumus')
@section('page-title', 'Konfigurasi Rumus Status Ketenagakerjaan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kelola Rumus</li>
@endsection

{{-- MENGGUNAKAN STYLES YANG SAMA DENGAN KELOLA QUEST --}}
@section('styles')
    <style>
        /* ===== STYLES DARI KONSISTENSI VISUAL ===== */
        /* Card Header Enhancement */
        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 2px solid #f0f0f0 !important;
        }

        /* Table Row Hover/Click Effect */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            cursor: pointer; /* Biarkan pointer karena baris ini bisa diklik/diperhatikan */
            transition: background-color 0.3s ease;
        }
        
        /* SOLUSI ALERT GANDA: Menyembunyikan alert dari layout induk */
        .main-content > .alert:first-child:not([data-handled="true"]),
        .main-content > .alert:nth-child(2):not([data-handled="true"]) {
            display: none !important; 
        }

        /* ===== ACTION BUTTON GROUP STYLES ===== */
        .btn-action-group {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .btn-custom {
            position: relative;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-custom:active {
            transform: translateY(-1px);
        }

        /* Style untuk tombol Update di tabel (termasuk Edit dan Aktifkan) */
        .btn-sm.btn-primary,
        .btn-sm.btn-success {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .btn-sm.btn-primary:hover,
        .btn-sm.btn-success:hover {
             transform: translateY(-1px);
             box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        /* Style Baris Aktif */
        .table-success {
            background-color: #d1e7dd !important; /* Hijau lebih soft dari default bootstrap */
            border-left: 5px solid #0f5132;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .table-responsive {
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
            }
        }
        
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Perbaikan Alert: Menggunakan style baru dan tag data-handled="true" --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show fade-in" role="alert" data-handled="true">
                        <i class="bi bi-check-circle-fill"></i> {!! nl2br(e(session('success'))) !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Alert untuk error (Jika ada) --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert" data-handled="true">
                        <i class="bi bi-exclamation-triangle-fill"></i> Terjadi kesalahan:
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow-sm fade-in">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Daftar Rumus</h5>
                        <small class="text-muted">Kelola logika penentuan status Bekerja/Pengangguran</small>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">Nama Rumus</th>
                                        <th>Deskripsi</th>
                                        <th style="width: 15%;" class="text-center">Status</th>
                                        <th style="width: 25%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rumus as $index => $r)
                                        <tr class="{{ $r->is_active ? 'table-success' : '' }} fade-in" style="animation-delay: {{ $index * 0.05 }}s;">
                                            <td><strong>{{ $r->nama_rumus }}</strong></td>
                                            <td>{{ $r->deskripsi }}</td>
                                            <td class="text-center">
                                                @if ($r->is_active)
                                                    <span class="badge bg-success">✅ Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">⏸️ Nonaktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-action-group justify-content-center">
                                                    <a href="{{ route('admin.config.rumus.edit', $r->id) }}"
                                                        class="btn btn-custom btn-sm btn-primary"
                                                        data-bs-toggle="tooltip" title="Ubah Logika Rumus">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>
                                                    @if (!$r->is_active)
                                                        <form action="{{ route('admin.config.rumus.toggle', $r->id) }}"
                                                            method="POST" style="display:inline;"
                                                            data-form-id="toggle-rumus-{{ $r->id }}">
                                                            @csrf
                                                            <button type="button" class="btn btn-custom btn-sm btn-success"
                                                                onclick="confirmToggle('{{ $r->id }}', '{{ $r->nama_rumus }}')">
                                                                <i class="bi bi-play-circle-fill"></i> Aktifkan
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white fade-in" style="animation-delay: 0.2s;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">Total {{ $rumus->count() }} Rumus Status</div>
                            <div class="btn-action-group">
                                <a href="{{ route('admin.config.quest') }}" class="btn btn-custom btn-info btn-sm" data-bs-toggle="tooltip" title="Kelola Pertanyaan Responden">
                                    <i class="bi bi-card-checklist"></i> Kelola Quest
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn btn-custom btn-secondary btn-sm" data-bs-toggle="tooltip" title="Kembali ke Halaman Utama">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- MODAL CUSTOM CONFIRM (MENGGANTIKAN JS CONFIRM) --}}
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Aktivasi Rumus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan mengaktifkan rumus: <strong id="rumusName"></strong>.</p>
                    <p class="text-danger small">Rumus yang aktif saat ini akan dinonaktifkan secara otomatis.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success btn-sm" id="confirmActivateBtn">Aktifkan Sekarang</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- Setup Variabel Global ---
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        const confirmActivateBtn = document.getElementById('confirmActivateBtn');
        let currentRumusId = null; 

        // ===================================
        // Animasi Fade-in (Konsistensi dengan halaman Quest)
        // ===================================
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        document.querySelectorAll('.fade-in').forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(10px)';
            element.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            observer.observe(element);
        });

        // ===================================
        // Bootstrap Tooltip
        // ===================================
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        // ===================================
        
        // ===================================
        // Logic Custom Confirm Modal
        // ===================================
        window.confirmToggle = function(rumusId, rumusName) {
            currentRumusId = rumusId;
            document.getElementById('rumusName').textContent = rumusName;
            confirmModal.show();
        };

        confirmActivateBtn.addEventListener('click', function() {
            if (currentRumusId) {
                // Sembunyikan modal
                confirmModal.hide();
                // Tampilkan loading saat form disubmit
                window.showLoading(); 
                
                // Submit form terkait
                const form = document.querySelector(`form[data-form-id="toggle-rumus-${currentRumusId}"]`);
                if (form) {
                    form.submit();
                } else {
                    console.error('Form tidak ditemukan untuk rumus ID:', currentRumusId);
                    window.hideLoading();
                }
            }
        });
        
        // Hapus fungsi default confirm agar form tidak ter-submit dua kali (di modal dan di HTML)
        // Note: Kita mengganti `onclick="return confirm('Aktifkan rumus ini?')"` dengan `onclick="confirmToggle(...)"`
    });
</script>
@endsection