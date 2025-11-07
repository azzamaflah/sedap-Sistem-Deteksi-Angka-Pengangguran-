@extends('layouts.app')

@section('title', 'Edit Rumus')
@section('page-title', 'Edit Rumus: ' . ($rumus->nama_rumus ?? 'Status Ketenagakerjaan'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.config.rumus') }}">Kelola Rumus</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('styles')
    {{-- PASTIKAN LINK BOOTSTRAP ICONS SUDAH ADA DI LAYOUTS/APP.BLADE.PHP --}}
    {{-- Jika belum, tambahkan ini di <head> app.blade.php: --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> --}}

    <style>
        /* === KONSISTENSI UMUM DENGAN HALAMAN ADMIN LAIN === */
        .card {
            border: none;
            border-radius: 0.5rem; /* Menyesuaikan dengan gaya Bootstrap 5 */
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); /* Shadow ringan */
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125) !important; /* Border bawah sesuai standar Bootstrap */
            color: #333;
            padding: 1.25rem 1.5rem; /* Padding standar Bootstrap header */
        }

        .card-header h5, .card-header small {
            margin-bottom: 0;
        }

        .form-check-input {
            width: 1.25em; /* Ukuran default Bootstrap */
            height: 1.25em; /* Ukuran default Bootstrap */
        }
        
        /* === ALERT GANDA FIX (Ini adalah bagian penting untuk mencegah duplikasi alert) === */
        /* Menyembunyikan alert dari layout induk yang TIDAK memiliki tag data-handled="true" */
        .main-content > .alert:first-child:not([data-handled="true"]),
        .main-content > .alert:nth-child(2):not([data-handled="true"]) {
            display: none !important; 
        }

        /* === STYLING KHUSUS UNTUK HALAMAN INI === */
        .json-editor {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace; /* Font monospace */
            background: #2d2d2d; /* Dark background */
            color: #f8f8f2; /* Light text */
            border-radius: 0.375rem; /* Border radius Bootstrap */
            padding: 1rem; /* Padding */
            min-height: 250px; /* Cukup tinggi */
            border: 1px solid #3e3e3e; /* Border sedikit lebih gelap */
            resize: vertical; /* Izinkan resize vertikal */
        }

        .help-section {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 1rem;
            margin-top: 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem; /* Sedikit lebih kecil */
        }
        .help-section pre {
            background: #ffffff;
            padding: 0.75rem;
            border-radius: 0.25rem;
            border: 1px solid #e0e0e0;
            white-space: pre-wrap; /* Pastikan baris panjang terbungkus */
            word-wrap: break-word;
        }

        .alert-info h6 {
            color: #055160; /* Warna teks info */
        }

        /* === BUTTON STYLING === */
        .btn-custom {
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-custom:hover {
            transform: translateY(-2px); /* Efek hover lebih halus */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-custom:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                
                {{-- ALERT BERHASIL --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show fade-in" role="alert" data-handled="true">
                        <i class="bi bi-check-circle-fill me-2"></i> {!! nl2br(e(session('success'))) !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Alert untuk Error/Validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert" data-handled="true">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> **Terjadi kesalahan:**
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="card shadow-sm fade-in">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">✏️ Edit Rumus Status: <span class="text-primary">{{ $rumus->nama_rumus }}</span></h5>
                        <small class="text-muted">Pastikan format JSON sudah benar sebelum menyimpan.</small>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.config.rumus.update', $rumus->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <h5>ℹ️ Informasi Rumus</h5>

                                <div class="mb-3">
                                    <label for="nama_rumus" class="form-label">Nama Rumus <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_rumus" id="nama_rumus"
                                        class="form-control @error('nama_rumus') is-invalid @enderror"
                                        value="{{ old('nama_rumus', $rumus->nama_rumus) }}" required>
                                    @error('nama_rumus')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $rumus->deskripsi) }}</textarea>
                                </div>

                                <div class="form-check form-switch mb-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                        value="1" {{ old('is_active', $rumus->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Aktifkan Rumus Ini</strong> 
                                        <small class="text-muted">(Rumus lain akan otomatis dinonaktifkan)</small>
                                    </label>
                                </div>

                                <hr class="my-4"> {{-- HR dengan margin --}}

                                <div class="mb-4">
                                    <h5>✅ Kondisi Status "Bekerja"</h5>
                                    <p class="text-muted">Definisikan kondisi yang harus dipenuhi agar responden berstatus
                                        <strong>Bekerja</strong></p>

                                    <label for="kondisi_bekerja" class="form-label">JSON Kondisi Bekerja <span
                                            class="text-danger">*</span></label>
                                    <textarea name="kondisi_bekerja" id="kondisi_bekerja" class="form-control json-editor @error('kondisi_bekerja') is-invalid @enderror"
                                        rows="10" required>{{ old('kondisi_bekerja', json_encode($rumus->kondisi_bekerja, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                                    @error('kondisi_bekerja')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div class="help-section">
                                        <strong>📖 Contoh Format JSON:</strong>
                                        <pre class="mb-0 mt-2">
{
 "operator": "OR",
 "conditions": [
  {"field": "r7_1", "value": "Ya"},
  {"field": "r7_2", "value": "Ya"},
  {"field": "r8_1", "value": "Ya"}
 ]
}</pre>
                                        <small class="text-muted mt-2 d-block">
                                            **Operator:** "OR" = salah satu kondisi terpenuhi | "AND" = semua
                                            kondisi harus terpenuhi. <br>
                                            **Catatan:** Kondisi dapat bertingkat (nested conditions).
                                        </small>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="mb-4">
                                    <h5>❌ Kondisi Status "Pengangguran"</h5>
                                    <p class="text-muted">Definisikan kondisi yang harus dipenuhi agar responden berstatus
                                        <strong>Pengangguran</strong></p>

                                    <label for="kondisi_pengangguran" class="form-label">JSON Kondisi Pengangguran <span
                                            class="text-danger">*</span></label>
                                    <textarea name="kondisi_pengangguran" id="kondisi_pengangguran"
                                        class="form-control json-editor @error('kondisi_pengangguran') is-invalid @enderror" rows="10" required>{{ old('kondisi_pengangguran', json_encode($rumus->kondisi_pengangguran, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                                    @error('kondisi_pengangguran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div class="help-section">
                                        <strong>📖 Contoh Format JSON:</strong>
                                        <pre class="mb-0 mt-2">
{
 "operator": "AND",
 "conditions": [
  {"field": "r7_1", "value": "Tidak"},
  {"field": "r8_1", "value": "Tidak"}
 ]
}</pre>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="alert alert-info shadow-sm fade-in" style="animation-delay: 0.1s;">
                                    <h6>📝 Field Quest yang Tersedia:</h6>
                                    <div class="row mt-3">
                                        @foreach ($quests as $quest)
                                            <div class="col-md-6 mb-2">
                                                <code>{{ $quest->key }}</code> - {{ $quest->label }}
                                                @if ($quest->type === 'radio' && $quest->options)
                                                    <br><small class="text-muted">Opsi:
                                                        {{ implode(', ', $quest->getOptionsArray()) }}</small>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('admin.config.rumus') }}" class="btn btn-secondary btn-custom">
                                        <i class="bi bi-arrow-left me-2"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-custom">
                                        <i class="bi bi-save me-2"></i> Simpan Perubahan
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===================================
            // Animasi Fade-in (Manual initialization untuk elemen non-table)
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
            // JSON Logic dan Validasi
            // ===================================
            const form = document.querySelector('form');
            const kondisiBekerja = document.querySelector('textarea[name="kondisi_bekerja"]');
            const kondisiPengangguran = document.querySelector('textarea[name="kondisi_pengangguran"]');

            function formatAndValidateJSON(textarea) {
                try {
                    const obj = JSON.parse(textarea.value);
                    textarea.value = JSON.stringify(obj, null, 2);
                    textarea.classList.remove('is-invalid');
                    return true;
                } catch (e) {
                    textarea.classList.add('is-invalid');
                    return false;
                }
            }

            // Pretty print JSON on load and re-format on blur/focus out
            if (kondisiBekerja) {
                formatAndValidateJSON(kondisiBekerja);
                kondisiBekerja.addEventListener('blur', () => formatAndValidateJSON(kondisiBekerja));
            }
            if (kondisiPengangguran) {
                formatAndValidateJSON(kondisiPengangguran);
                kondisiPengangguran.addEventListener('blur', () => formatAndValidateJSON(kondisiPengangguran));
            }

            form.addEventListener('submit', function(e) {
                let validBekerja = true;
                let validPengangguran = true;

                if (kondisiBekerja) {
                    validBekerja = formatAndValidateJSON(kondisiBekerja);
                    if (!validBekerja) {
                        e.preventDefault();
                        if(window.showAlert) {
                            window.showAlert('❌ Format JSON Kondisi Bekerja tidak valid!', 'danger');
                        } else {
                            alert('❌ Format JSON Kondisi Bekerja tidak valid!');
                        }
                    }
                }

                if (kondisiPengangguran) {
                    validPengangguran = formatAndValidateJSON(kondisiPengangguran);
                    if (!validPengangguran && validBekerja) { // Hanya prevent default jika belum dilakukan oleh validBekerja
                        e.preventDefault();
                        if(window.showAlert) {
                            window.showAlert('❌ Format JSON Kondisi Pengangguran tidak valid!', 'danger');
                        } else {
                            alert('❌ Format JSON Kondisi Pengangguran tidak valid!');
                        }
                    } else if (!validPengangguran && !validBekerja) {
                         // Jika kedua-duanya invalid, alert akan sudah muncul dari validBekerja
                         // atau akan muncul dari validPengangguran, tidak perlu preventDefault lagi.
                    }
                }

                if (validBekerja && validPengangguran) {
                    return confirm('💾 Simpan perubahan rumus ini?');
                }
            });
            
            // ===================================
            // Bootstrap Tooltip (jika diperlukan)
            // ===================================
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection