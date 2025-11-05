@extends('layouts.app')

@section('title', 'Sampel Rumah Tangga')
@section('page-title', 'Sampel Rumah Tangga')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Sampel Rumah Tangga</li>
@endsection

@section('styles')
    <style>
        /* ===== STYLES DARI DASHBOARD.BLADE.PHP UNTUK KONSISTENSI VISUAL ===== */
        /* Card Header Enhancement */
        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 2px solid #f0f0f0 !important;
        }


        /* System Info Item (Digunakan untuk highlight baris) */
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

        /* Table Row Hover/Click Effect */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05); /* Light primary color hover */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* ===== STYLES KHUSUS DSRT (DIKOMBINASIKAN) ===== */
        .btn-action-group {
            display: flex;
            gap: 5px; /* Mengurangi gap untuk tombol ikon */
            align-items: center;
        }

        .btn-custom {
            position: relative;
            /* Hapus overflow: hidden; untuk mencegah badge terpotong */
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* .btn-custom::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        } */

        .btn-custom:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-custom:active {
            transform: translateY(-1px);
        }

        /* Import Button - Gradient Cyan */
        .btn-import {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .btn-import:hover {
            background: linear-gradient(135deg, #138496 0%, #0f6674 100%);
            color: white;
        }

        /* Export Button - Gradient Green */
        .btn-export {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
            position: relative;
            z-index: 1; /* Pastikan tombol di bawah badge, tapi di atas elemen lain */
        }

        .btn-export:hover:not(:disabled) {
            background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
            color: white;
        }

        .btn-export:disabled {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            cursor: not-allowed;
            box-shadow: none;
            opacity: 0.6;
        }

        /* Add Button - Gradient Primary (Warna Biru Konsisten) */
        .btn-add {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            color: white;
        }

        /* Badge Counter untuk Export */
        .export-counter {
            /* **PERBAIKAN Z-INDEX DAN POSISI AGAR TERLIHAT JELAS** */
            position: absolute;
            top: -10px; /* Jarak lebih jauh dari atas */
            right: -10px; /* Jarak lebih jauh dari kanan */
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
            animation: pulse 2s infinite;
            z-index: 99; /* Z-index sangat tinggi */
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Icon Animation */
        .btn-custom i {
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .btn-custom:hover i {
            transform: scale(1.2) rotate(5deg);
        }

        .btn-import:hover i {
            animation: slideUp 0.5s ease;
        }

        .btn-export:hover:not(:disabled) i {
            animation: slideDown 0.5s ease;
        }

        .btn-add:hover i {
            animation: rotate360 0.6s ease;
        }

        @keyframes slideUp {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        @keyframes slideDown {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(5px);
            }
        }

        @keyframes rotate360 {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Search Bar Enhancement */
        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-wrapper .form-control {
            padding-left: 25px;
            padding-right: 90px;
            border-radius: 50px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            height: 45px;
            width: 100%;
        }

        .search-wrapper .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
            outline: none;
        }

        .search-wrapper .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 18px;
            z-index: 10;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .search-wrapper .form-control:focus~.search-icon {
            color: #007bff;
            animation: searchPulse 0.5s ease;
        }

        @keyframes searchPulse {

            0%,
            100% {
                transform: translateY(-50%) scale(1);
            }

            50% {
                transform: translateY(-50%) scale(1.2);
            }
        }

        /* Clear Button */
        .btn-clear-search {
            position: absolute;
            right: 50px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #dc3545;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-clear-search:hover {
            color: #bd2130;
            transform: translateY(-50%) scale(1.2) rotate(90deg);
        }

        /* Search Submit Button */
        .search-wrapper .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }

        .search-wrapper .search-btn:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-50%) scale(1.15);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        }

        .search-wrapper .search-btn i {
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .search-wrapper .search-btn:hover i {
            transform: scale(1.2);
        }
        
        /* Modifikasi Tombol Aksi */
        .btn-action-group .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .btn-action-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-custom {
                width: 100%;
            }

            .search-wrapper .form-control {
                padding-right: 50px;
            }

            .btn-clear-search {
                display: none;
            }
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
            <i class="bi bi-check-circle-fill"></i> {!! nl2br(e(session('success'))) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show fade-in" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <pre class="mb-0" style="white-space: pre-wrap;">{!! e(session('warning')) !!}</pre>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <pre class="mb-0" style="white-space: pre-wrap;">{!! e(session('error')) !!}</pre>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm fade-in">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center g-3">
                <div class="col-lg-5 col-md-12">
                    <form method="GET" class="search-wrapper">
                        <input type="text" name="search" id="searchInput" class="form-control"
                            placeholder="Cari kecamatan, desa, atau blok sensus..." value="{{ request('search') }}">

                        @if (request('search'))
                            <a href="{{ route('dsrt.index') }}" class="btn-clear-search" data-bs-toggle="tooltip"
                                title="Hapus Pencarian">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif

                        <button type="submit" class="search-btn" data-bs-toggle="tooltip" title="Cari">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <div class="col-lg-7 col-md-12">
                    <div class="btn-action-group justify-content-lg-end">
                        <button type="button" class="btn btn-custom btn-import" data-bs-toggle="modal"
                            data-bs-target="#modalImport" data-bs-toggle="tooltip" title="Import data dari Excel">
                            <i class="bi bi-file-earmark-arrow-up"></i> Import Excel
                        </button>

                        <button type="button" class="btn btn-custom btn-export" id="btnOpenExportModal" disabled
                            data-bs-toggle="tooltip" title="Export data yang terpilih">
                            <i class="bi bi-file-earmark-excel"></i> Export Terpilih
                            <span class="export-counter d-none" id="exportCounter">0</span>
                        </button>

                        <a href="{{ route('dsrt.create') }}" class="btn btn-custom btn-add" data-bs-toggle="tooltip"
                            title="Tambah data baru">
                            <i class="bi bi-plus-circle"></i> Tambah Sampel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                {{-- PERUBAHAN: Menghilangkan class table-striped --}}
                <table class="table table-hover mb-0 align-middle"> 
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 40px;">
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th>Kecamatan</th>
                            <th>Desa</th>
                            <th class="text-center">ID BS</th>
                            <th class="text-center">ID NKS</th>
                            <th class="text-center">No Urut RT</th>
                            <th class="text-center">Hasil Pencacahan</th>
                            <th class="text-center" style="width: 120px;">Aksi</th> {{-- Dikecilkan --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr class="fade-in"> {{-- Tambahkan class fade-in --}}
                                <td class="text-center">
                                    <input type="checkbox" class="checkbox-item form-check-input"
                                        value="{{ $row->no }}">
                                </td>
                                <td class="text-center">
                                    {{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}
                                </td>
                                <td>
                                    <strong>{{ $row->kecamatan->nama_kec ?? $row->id_kec }}</strong>
                                </td>
                                <td>{{ $row->desa->nama_desa ?? $row->id_desa }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $row->id_bs }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark">{{ $row->id_nks ?? '-' }}</span>
                                </td>
                                <td class="text-center">{{ $row->id_nurt }}</td>
                                <td class="text-center">
                                    @if ($row->respon == 'Respon')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Respon
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i> Non Respon
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- MODIFIKASI: Ikon saja, btn-outline-primary, lebih simple --}}
                                    <div class="btn-group btn-action-group" role="group">
                                        <a href="{{ route('dsrt.edit', $row->no) }}" class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="tooltip" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        {{-- MODIFIKASI: Ikon saja, btn-outline-danger, lebih simple --}}
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip"
                                            title="Hapus Data" onclick="confirmDelete({{ $row->no }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $row->no }}"
                                        action="{{ route('dsrt.destroy', $row->no) }}" method="POST"
                                        style="display: none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 fade-in" style="animation-delay: 0.1s;">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Tidak ada data sampel rumah tangga.</p>
                                    <a href="{{ route('dsrt.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle"></i> Tambah Data Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white fade-in" style="animation-delay: 0.2s;">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
        
    </div>

    <div class="modal fade" id="modalExport" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="export-form" method="POST" action="{{ route('dsrt.export') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalExportLabel">
                            <i class="bi bi-file-earmark-excel"></i> Export Data Terpilih
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Data Format</label>
                            <select name="data_format" class="form-select" required>
                                <option value="formatted">Formatted Values (Dengan Nama)</option>
                                <option value="raw">Raw Values (Kode Saja)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Output Format</label>
                            <select name="output_format" class="form-select" required>
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="csv">CSV (.csv)</option>
                            </select>
                        </div>
                        <div id="selected-data-preview" class="alert alert-info small">
                            <i class="bi bi-info-circle"></i> <span id="selectedCount">0</span> data dipilih
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-download"></i> Export Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalImportLabel">
                        <i class="bi bi-file-earmark-arrow-up"></i> Import Data Sampel Rumah Tangga
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dsrt.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning border-warning system-info-item">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-info-circle-fill"></i> Tata Cara Pengisian Template
                            </h6>
                            <ol class="mb-0 small">
                                <li class="mb-2">
                                    <strong>id_kec (3 digit):</strong> Isi kode kecamatan yang ADA di database.<br>
                                    <code>Contoh: 010 (Srandakan), 060 (Pandak), 070 (Bantul)</code>
                                </li>
                                <li class="mb-2">
                                    <strong>id_desa (6 digit):</strong> Isi kode desa yang ADA di database dan SESUAI dengan
                                    kecamatan.<br>
                                    <code>Contoh: 010001 (Poncosari), 060003 (Gilangharjo)</code>
                                    <br><span class="text-danger">⚠️ PENTING: Desa harus termasuk dalam kecamatan yang
                                        dipilih!</span>
                                </li>
                                <li class="mb-2">
                                    <strong>id_bs (4 digit):</strong> Isi kode Blok Sensus.<br>
                                    <code>Contoh: 0001, 0002, 1, 12</code>
                                </li>
                                <li class="mb-2">
                                    <strong>id_nks (6 digit):</strong> Isi kode NKS.<br>
                                    <code>Contoh: 000101, 000201, 101</code>
                                </li>
                                <li class="mb-2">
                                    <strong>id_nurt:</strong> Nomor urut rumah tangga (1-10).
                                </li>
                                <li class="mb-2">
                                    <strong>respon:</strong> Hanya <b>'Respon'</b> atau <b>'Non Respon'</b>.
                                </li>
                            </ol>
                            <div class="alert alert-info mt-3 mb-0">
                                <small>
                                    <i class="bi bi-lightbulb"></i> <strong>Tips:</strong><br>
                                    • Kode otomatis dipadding nol jika perlu<br>
                                    • Data tervalidasi otomatis saat import<br>
                                    • Download template sebagai contoh
                                </small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-1-circle-fill text-success"></i> Download Template Excel
                            </label>
                            <a href="{{ route('dsrt.template') }}" class="btn btn-success w-100" target="_blank">
                                <i class="bi bi-download"></i> Download Template Excel
                            </a>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-2-circle-fill text-primary"></i> Upload File Excel yang Sudah Diisi
                            </label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted">
                                <i class="bi bi-file-earmark-excel"></i> Format: .xlsx, .xls, .csv (Maks. 2MB)
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            
            // Bootstrap Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Konfirmasi Delete
            window.confirmDelete = function(id) {
                if (confirm(
                        '⚠️ Yakin ingin menghapus data ini?\n\nData yang sudah dihapus tidak dapat dikembalikan!'
                    )) {
                    document.getElementById('delete-form-' + id).submit();
                }
            };

            // EXPORT: Checklist, modal, transfer data
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.checkbox-item');
            const btnExportOpen = document.getElementById('btnOpenExportModal');
            const exportCounter = document.getElementById('exportCounter');
            let selected = [];

            function updateBtn() {
                selected = Array.from(document.querySelectorAll('.checkbox-item:checked')).map(cb => cb.value);
                const count = selected.length;

                if (btnExportOpen) {
                    btnExportOpen.disabled = count === 0;
                }

                if (exportCounter) {
                    if (count > 0) {
                        exportCounter.textContent = count;
                        exportCounter.classList.remove('d-none');
                    } else {
                        exportCounter.classList.add('d-none');
                    }
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    updateBtn();
                });

                checkboxes.forEach(cb => {
                    cb.addEventListener('change', function() {
                        if (!this.checked) selectAll.checked = false;
                        if (document.querySelectorAll('.checkbox-item:checked').length ===
                            checkboxes.length) {
                            selectAll.checked = true;
                        }
                        updateBtn();
                    });
                });

                updateBtn();
            }

            if (btnExportOpen) {
                btnExportOpen.addEventListener('click', function(e) {
                    // Hapus semua hidden input selected[]
                    document.querySelectorAll('#export-form input[name="selected[]"]').forEach(el => el
                        .remove());

                    // Tambah hidden input untuk setiap data terpilih
                    selected.forEach(val => {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected[]';
                        input.value = val;
                        document.getElementById('export-form').appendChild(input);
                    });

                    // Update counter di modal
                    const selectedCount = document.getElementById('selectedCount');
                    if (selectedCount) {
                        selectedCount.textContent = selected.length;
                    }

                    // Buka modal
                    const modal = new bootstrap.Modal(document.getElementById('modalExport'));
                    modal.show();
                });
            }
        });
    </script>
@endsection