@extends('layouts.app')

@section('title', 'Sampel Rumah Tangga')
@section('page-title', 'Sampel Rumah Tangga')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Sampel Rumah Tangga</li>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari..."
                    value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </form>
            <div class="d-flex gap-2">
                <!-- Button Import -->
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-file-earmark-arrow-up"></i> Import Excel
                </button>
                <!-- Button Export -->
                <button type="button" class="btn btn-success" id="btnOpenExportModal" disabled>
                    <i class="bi bi-file-earmark-excel"></i> Export Terpilih
                </button>
                <!-- Button Tambah -->
                <a href="{{ route('dsrt.create') }}" class="btn btn-success">
                    <i class="bi bi-plus"></i> Tambah Sampel
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width:40px"><input type="checkbox" id="select-all"></th>
                            <th>No</th>
                            <th>Kecamatan</th>
                            <th>Desa</th>
                            <th>ID BS</th>
                            <th>ID NKS</th>
                            <th>No Urut RT</th>
                            <th>Hasil Pencacahan</th>
                            <th style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td><input type="checkbox" class="checkbox-item" value="{{ $row->no }}"></td>
                                <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                <td>{{ $row->kecamatan->nama_kec ?? $row->id_kec }}</td>
                                <td>{{ $row->desa->nama_desa ?? $row->id_desa }}</td>
                                <td>{{ $row->id_bs }}</td>
                                <td>{{ $row->id_nks ?? '-' }}</td>
                                <td>{{ $row->id_nurt }}</td>
                                <td>
                                    @if ($row->respon == 'Respon')
                                        <span class="badge bg-success">Respon</span>
                                    @else
                                        <span class="badge bg-danger">Non Respon</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('dsrt.edit', $row->no) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('dsrt.destroy', $row->no) }}" method="POST"
                                        style="display:inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- MODAL EXPORT -->
    <div class="modal fade" id="modalExport" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="export-form" method="POST" action="{{ route('dsrt.export') }}">
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

    <!-- MODAL IMPORT, DENGAN TEMPLATE DI DALAM MODAL -->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalImportLabel">
                        <i class="bi bi-file-earmark-arrow-up"></i> Import Data Sampel Rumah Tangga
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('dsrt.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Instruksi -->
                        <div class="alert alert-warning border-warning">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-info-circle-fill"></i> Tata Cara Pengisian Template
                            </h6>
                            <ol class="mb-0 small">
                                <li class="mb-2">
                                    <strong>id_kec (3 digit):</strong>
                                    Isi kode kecamatan yang ADA di database.<br>
                                    <code>Contoh: 010 (Srandakan), 060 (Pandak), 070 (Bantul)</code>
                                </li>
                                <li class="mb-2">
                                    <strong>id_desa (6 digit):</strong>
                                    Isi kode desa yang ADA di database dan SESUAI dengan kecamatan.<br>
                                    <code>Contoh: 010001 (Poncosari di Srandakan), 060003 (Gilangharjo di Pandak)</code>
                                    <br>
                                    <span class="text-danger">⚠️ PENTING: Desa harus termasuk dalam kecamatan yang
                                        dipilih!</span>
                                </li>
                                <li class="mb-2">
                                    <strong>id_bs (4 digit):</strong>
                                    Isi kode Blok Sensus (harus ada pada kombinasi kecamatan dan desa yang diinput).<br>
                                    <code>Contoh: 0001, 0002, 1, 12</code>
                                </li>
                                <li class="mb-2">
                                    <strong>id_nks (6 digit):</strong>
                                    Isi kode NKS (harus sesuai blok sensus).<br>
                                    <code>Contoh: 000101, 000201, 101</code>
                                </li>
                                <li class="mb-2">
                                    <strong>id_nurt:</strong> Nomor urut rumah tangga (isi angka 1-10 saja).
                                </li>
                                <li class="mb-2">
                                    <strong>respon:</strong>
                                    Hanya boleh diisi <b>'Respon'</b> atau <b>'Non Respon'</b>.<br>
                                    <span class="text-success">✅ Format bebas besar kecil, misal: Respon, RESpON, non
                                        respon</span>
                                </li>
                            </ol>
                            <div class="alert alert-info mt-3 mb-0">
                                <small>
                                    <i class="bi bi-lightbulb"></i> <strong>Tips:</strong><br>
                                    • Kolom kode otomatis dipadding nol jika perlu<br>
                                    • Data akan tervalidasi otomatis dengan database pada saat import<br>
                                    • Download template di bawah sebagai contoh struktur Excel yang benar
                                </small>
                            </div>
                        </div>
                        <!-- Download Template -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-1-circle-fill text-success"></i> Download Template Excel
                            </label>
                            <a href="{{ route('dsrt.template') }}" class="btn btn-success w-100" target="_blank">
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
            // Tooltip
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
                    document.querySelectorAll('#export-form input[name="selected[]"]').forEach(el => el
                        .remove());
                    selected.forEach(val => {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected[]';
                        input.value = val;
                        document.getElementById('export-form').appendChild(input);
                    });
                    const preview = document.getElementById('selected-data-preview');
                    if (preview) {
                        preview.classList.toggle('d-none', selected.length == 0);
                        preview.textContent = selected.length ? (`${selected.length} data terpilih`) : '';
                    }
                    const modal = new bootstrap.Modal(document.getElementById('modalExport'));
                    modal.show();
                });
            }
        });
    </script>
@endsection
