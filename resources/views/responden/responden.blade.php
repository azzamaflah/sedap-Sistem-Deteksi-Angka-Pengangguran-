@extends('layouts.app')

@section('title', 'Data Responden')
@section('page-title', 'Data Responden')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Responden</li>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {!! nl2br(e(session('success'))) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <pre class="mb-0" style="white-space: pre-wrap;">{!! e(session('warning')) !!}</pre>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <pre class="mb-0" style="white-space: pre-wrap;">{!! e(session('error')) !!}</pre>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <form method="GET" class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
                <input type="text" name="search" class="form-control" placeholder="🔍 Cari nama responden..."
                    value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Cari
                </button>
            </form>
            <div class="d-flex gap-2">
                <!-- Button Import -->
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-file-earmark-arrow-up"></i> Import Excel
                </button>
                <!-- Button Tambah -->
                <a href="{{ route('responden.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Tambah Responden
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="mb-3 p-3">
                <button type="button" class="btn btn-success" id="btnOpenExportModal" disabled>
                    <i class="bi bi-file-earmark-excel"></i> Export Terpilih
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 40px;">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th>Kecamatan</th>
                            <th>Desa</th>
                            <th class="text-center">ID BS</th>
                            <th class="text-center">ID NKS</th>
                            <th class="text-center">No Urut RT</th>
                            <th>Nama Responden</th>
                            <th class="text-center">Status Pekerjaan</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="checkbox-item" value="{{ $row->no }}">
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
                                <td><strong>{{ $row->nama_sample }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $row->status_badge_color }}">
                                        {{ $row->status_pekerjaan }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('responden.edit', $row->no) }}" class="btn btn-warning btn-sm"
                                            data-bs-toggle="tooltip" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                            title="Hapus Data" onclick="confirmDelete({{ $row->no }})">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $row->no }}"
                                        action="{{ route('responden.destroy', $row->no) }}" method="POST"
                                        style="display: none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Tidak ada data responden.</p>
                                    <a href="{{ route('responden.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle"></i> Tambah Data Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- MODAL EXPORT -->
    <div class="modal fade" id="modalExport" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="export-form" method="POST" action="{{ route('responden.export') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalExportLabel">Export Data Terpilih</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Data Format</label>
                            <select name="data_format" class="form-select" required>
                                <option value="formatted">Formatted Values</option>
                                <option value="raw">Raw Values</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Output Format</label>
                            <select name="output_format" class="form-select" required>
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="csv">CSV (.csv)</option>
                            </select>
                        </div>
                        <div id="selected-data-preview" class="alert alert-info small d-none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-download"></i> Export Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL IMPORT -->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalImportLabel">
                        <i class="bi bi-file-earmark-arrow-up"></i> Import Data Responden
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('responden.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Instruksi -->
                        <div class="alert alert-warning border-warning">
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
                                    <code>Contoh: 010001 (Poncosari di Srandakan), 060003 (Gilangharjo di Pandak)</code>
                                    <br><span class="text-danger">⚠️ PENTING: Desa harus termasuk dalam kecamatan yang
                                        dipilih!</span>
                                </li>
                                <li class="mb-2">
                                    <strong>id_bs (4 digit):</strong> Isi kode Blok Sensus (harus ada pada kombinasi
                                    kecamatan dan desa).<br>
                                    <code>Contoh: 0001, 0002, 1, 12</code>
                                </li>
                                <li class="mb-2">
                                    <strong>id_nks (6 digit):</strong> Isi kode NKS (harus sesuai blok sensus).<br>
                                    <code>Contoh: 000101, 000201, 101</code>
                                </li>
                                <li class="mb-2">
                                    <strong>id_nurt:</strong> Nomor urut rumah tangga (isi angka 1-10 saja).
                                </li>
                                <li class="mb-2">
                                    <strong>nama_sample:</strong>
                                    Nama responden usia 15 tahun ke atas (minimal 3 karakter, hanya huruf & spasi).<br>
                                    <code>Contoh: Budi Santoso, Siti Nurhaliza</code>
                                    <br><span class="text-danger">⚠️ Nama tidak boleh aneh atau hanya angka!</span>
                                </li>
                                <li class="mb-2">
                                    <strong>r7_1 sampai r20_4:</strong> Kolom jawaban responden (boleh diisi, boleh
                                    dikosong).<br>
                                    <span class="text-muted">Jika kosong, bisa diisi nanti melalui form edit.</span>
                                </li>
                            </ol>
                            <div class="alert alert-info mt-3 mb-0">
                                <small>
                                    <i class="bi bi-lightbulb"></i> <strong>Tips:</strong><br>
                                    • Kolom kode otomatis dipadding nol jika perlu<br>
                                    • Data akan tervalidasi otomatis dengan database pada saat import<br>
                                    • Pastikan isian jawaban responden sesuai dengan tata cara alur pengisian di dokumen
                                    agar klasifikasi bekerja dan pengangguran sesuai dengan kondisi yang sesungguhnya<br>
                                    • Download template di bawah sebagai contoh struktur Excel yang benar
                                </small>
                            </div>
                        </div>
                        <!-- Download Template -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-1-circle-fill text-success"></i> Download Template Excel
                            </label>
                            <a href="{{ route('responden.template') }}" class="btn btn-success w-100" target="_blank">
                                <i class="bi bi-download"></i> Download Template Excel
                            </a>
                        </div>
                        <!-- Upload File -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-2-circle-fill text-primary"></i> Upload File Excel yang Sudah Diisi
                            </label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted">
                                <i class="bi bi-file-earmark-excel"></i> Format yang didukung: .xlsx, .xls, .csv (Maks.
                                2MB)
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
            // Bootstrap Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
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
            let selected = [];

            function updateBtn() {
                selected = Array.from(document.querySelectorAll('.checkbox-item:checked')).map(cb => cb.value);
                if (btnExportOpen) btnExportOpen.disabled = selected.length === 0;
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
                    // Hapus dulu semua hidden input selected[]
                    document.querySelectorAll('#export-form input[name="selected[]"]').forEach(el => el
                        .remove());

                    // Tambah input hidden selected[]
                    selected.forEach(val => {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected[]';
                        input.value = val;
                        document.getElementById('export-form').appendChild(input);
                    });

                    // Preview berapa yang dipilih
                    const preview = document.getElementById('selected-data-preview');
                    if (preview) {
                        preview.classList.toggle('d-none', selected.length == 0);
                        preview.textContent = selected.length ? (`${selected.length} data terpilih`) : '';
                    }

                    // Buka modal bootstrap
                    const modal = new bootstrap.Modal(document.getElementById('modalExport'));
                    modal.show();
                });
            }
        });
    </script>
@endsection
