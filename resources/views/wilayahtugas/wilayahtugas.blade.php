@extends('layouts.app')

@section('title', 'Wilayah Tugas')

@section('page-title', 'Wilayah Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Wilayah Tugas</li>
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
                <input type="text" name="search" class="form-control"
                    placeholder="🔍 Cari kecamatan, desa, atau blok..." value="{{ request('search') }}">
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
                <a href="{{ route('wilayahTugas.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Tambah Data
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="mb-3">
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
                            <th>Pengawas</th>
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

                                {{-- ✅ PERBAIKAN: Kolom Pengawas --}}
                                <td>
                                    @if ($row->id_user && $row->pengawas)
                                        {{-- ✅ Jika ada id_user DAN relasi pengawas berhasil diload --}}
                                        <i class="bi bi-person-badge text-primary"></i>
                                        <strong>{{ $row->pengawas->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $row->pengawas->email }}</small>
                                    @elseif ($row->id_user && !$row->pengawas)
                                        {{-- ⚠️ Ada id_user tapi user sudah dihapus dari database --}}
                                        <span class="text-warning">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            User ID: {{ $row->id_user }} (Tidak ditemukan)
                                        </span>
                                    @else
                                        {{-- ❌ Belum ada pengawas ditugaskan --}}
                                        <span class="text-muted">
                                            <i class="bi bi-dash-circle"></i> Belum ditugaskan
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <!-- Button Edit -->
                                        <a href="{{ route('wilayahTugas.edit', $row->no) }}" class="btn btn-warning btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <!-- Button Delete -->
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Hapus Data"
                                            onclick="confirmDelete({{ $row->no }})">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>

                                    <!-- Form Delete (Hidden) -->
                                    <form id="delete-form-{{ $row->no }}"
                                        action="{{ route('wilayahTugas.destroy', $row->no) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Tidak ada data wilayah tugas.</p>
                                    <a href="{{ route('wilayahTugas.create') }}" class="btn btn-primary btn-sm">
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

    <!-- Tombol export -->
    <button type="button" class="btn btn-success" id="btnOpenExportModal" disabled>
        <i class="bi bi-file-earmark-excel"></i> Export Terpilih
    </button>

    <div class="modal fade" id="modalExport" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="export-form" method="POST" action="{{ route('wilayahTugas.export') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalExportLabel">Export Data Terpilih</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Nanti input hidden untuk selected[] akan disisipkan via JS --}}
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
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
                        <i class="bi bi-file-earmark-arrow-up"></i> Import Data Wilayah Tugas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form action="{{ route('wilayahTugas.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Instruksi -->
                        <div class="alert alert-warning border-warning">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-info-circle-fill"></i> Tata Cara Pengisian Template
                            </h6>
                            <ol class="mb-0 small">
                                <li class="mb-2">
                                    <strong>id_kec (3 digit):</strong> Isi kode kecamatan yang ADA di database
                                    <br>
                                    <code>Contoh: 010 (Srandakan), 060 (Pandak), 070 (Bantul)</code>
                                </li>
                                <li class="mb-2">
                                    <strong>id_desa (6 digit):</strong> Isi kode desa yang ADA di database dan SESUAI dengan
                                    kecamatan
                                    <br>
                                    <code>Contoh: 010001 (Poncosari di Srandakan), 060003 (Gilangharjo di Pandak)</code>
                                    <br>
                                    <span class="text-danger">⚠️ PENTING: Desa harus termasuk dalam kecamatan yang
                                        dipilih!</span>
                                </li>
                                <li class="mb-2">
                                    <strong>id_bs (4 digit):</strong> Isi kode Blok Sensus (bisa angka bebas, boleh
                                    duplikat)
                                    <br>
                                    <code>Contoh: 0001, 0002, 1, 12</code>
                                    <br>
                                    <span class="text-success">✅ ID BS boleh sama dengan data lain</span>
                                </li>
                                <li class="mb-2">
                                    <strong>id_nks (6 digit):</strong> Isi kode Nomor Kode Sample (opsional, boleh duplikat)
                                    <br>
                                    <code>Contoh: 000101, 000201, 101</code>
                                    <br>
                                    <span class="text-success">✅ ID NKS boleh sama dengan data lain (1 NKS bisa punya
                                        banyak data)</span>
                                </li>
                                <li class="mb-2">
                                    <strong>nama_pengawas:</strong> Isi nama pengawas yang ADA di database users
                                    <br>
                                    <code>Contoh: admin</code>
                                    <br>
                                    <span class="text-muted">Jika tidak ditemukan, data tetap masuk tanpa pengawas</span>
                                </li>
                            </ol>

                            <div class="alert alert-info mt-3 mb-0">
                                <small>
                                    <i class="bi bi-lightbulb"></i> <strong>Tips:</strong><br>
                                    • Sistem akan otomatis menambahkan padding 0 (contoh: 10 → 010, 1 → 0001)<br>
                                    • ID BS dan ID NKS boleh duplikat (tidak ada validasi unique)<br>
                                    • Hanya kode kecamatan dan desa yang akan divalidasi ketat<br>
                                    • Download template untuk melihat contoh data yang benar
                                </small>
                            </div>
                        </div>

                        <!-- Download Template -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-1-circle-fill text-success"></i> Download Template Excel
                            </label>
                            <a href="{{ route('wilayahTugas.template') }}" class="btn btn-success w-100">
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
                                <i class="bi bi-file-earmark-excel"></i> Format yang didukung: .xlsx, .xls, .csv (Maksimal:
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
        // Bootstrap Tooltip
        document.addEventListener('DOMContentLoaded', function() {
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
