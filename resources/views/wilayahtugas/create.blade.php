@extends('layouts.app')

@section('title', 'Tambah Wilayah Tugas')

@section('page-title', 'Tambah Wilayah Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('wilayahTugas.index') }}">Wilayah Tugas</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('styles')
    <style>
        /* Card Header Enhancement - Digunakan pada layout utama tapi diduplikasi untuk konsistensi jika card di-render cepat */
        .card-header {
            /* Warna header khusus di dalam card ini akan di-override di markup,
                   tapi style ini memastikan konsistensi border dan padding */
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 2px solid #f0f0f0 !important;
            padding-top: 1.2rem;
            padding-bottom: 1.2rem;
        }



        /* Mengaplikasikan style pada card form */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Card Footer Styling */
        card-header .card-footer {
            border-top: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }

        /* Grouping untuk memisahkan bagian form secara visual */
        .form-group-title {
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }

        /* Select styling for consistency */
        .form-select:disabled {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        /* PERBAIKAN TOMBOL SIMPAN (Konsisten) */
        .card-footer .btn-primary {
            color: white !important;
            background-color: #007bff !important;
            opacity: 1 !important;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2) !important;
        }

        .card-footer .btn-primary:hover {
            background-color: #0056b3 !important;
        }

        /* Style untuk Header Section di dalam Form */
        .card .header-section {
            padding: 1.25rem;
            color: white;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm fade-in">
        <form method="POST" action="{{ route('wilayahTugas.store') }}">
            @csrf

            {{-- HEADER SECTION: Informasi Wilayah --}}
            <div class="header-section bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i> Penugasan Wilayah Baru</h5>
            </div>

            <div class="card-body row g-4">

                {{-- Bagian 1: Informasi Wilayah (2 Kolom) --}}
                <div class="col-12">
                    <h6 class="form-group-title">
                        <i class="bi bi-map me-1"></i> Data Geografis
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <select name="id_kec" id="kecamatan" class="form-select" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($kecamatan as $kec)
                                    <option value="{{ $kec->id_kec }}"
                                        {{ old('id_kec') == $kec->id_kec ? 'selected' : '' }}>
                                        {{ $kec->nama_kec }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kec')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Desa <span class="text-danger">*</span></label>
                            <select name="id_desa" id="desa" class="form-select" required disabled>
                                <option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>
                            </select>
                            @error('id_desa')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Horizontal Separator --}}
                <div class="col-12">
                    <hr class="mt-0 mb-0">
                </div>

                {{-- Bagian 2: Detail Blok dan Pengawas (3 Kolom) --}}
                <div class="col-12">
                    <h6 class="form-group-title">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i> Detail Blok dan Penugasan
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Blok Sensus (ID BS) <span class="text-danger">*</span></label>
                            <input type="text" name="id_bs" class="form-control" placeholder="Contoh: 0001"
                                value="{{ old('id_bs') }}" required>
                            @error('id_bs')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nomor Kode Sample (ID NKS)</label>
                            <input type="text" name="id_nks" class="form-control" placeholder="Contoh: 000101"
                                value="{{ old('id_nks') }}">
                            @error('id_nks')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nama Pengawas <span class="text-danger">*</span></label>
                            <select name="id_user" class="form-select" required>
                                <option value="">-- Pilih Pengawas --</option>
                                @foreach ($pengawas as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('id_user') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_user')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('wilayahTugas.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logic Animasi Fade-in
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


            // Memastikan fungsi showLoading/hideLoading global tersedia jika form disubmit
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    if (window.showLoading) {
                        window.showLoading();
                    }
                });
            }

            // --- Dynamic Dropdown: Kecamatan -> Desa ---

            const kecamatanSelect = document.getElementById('kecamatan');
            const desaSelect = document.getElementById('desa');

            function loadDesa(idKec, preselectedValue = null) {
                desaSelect.innerHTML = '<option value="">-- Loading... --</option>';
                desaSelect.disabled = true;

                if (idKec) {
                    // Fetch desa berdasarkan kecamatan
                    fetch(`/api/desa/${idKec}`) // Pastikan API endpoint ini benar
                        .then(response => response.json())
                        .then(data => {
                            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
                            if (data.length > 0) {
                                data.forEach(desa => {
                                    const option = document.createElement('option');
                                    option.value = desa.id_desa;
                                    option.textContent = desa.nama_desa;
                                    if (preselectedValue && desa.id_desa == preselectedValue) {
                                        option.selected = true;
                                    }
                                    desaSelect.appendChild(option);
                                });
                                desaSelect.disabled = false;
                            } else {
                                desaSelect.innerHTML = '<option value="">-- Tidak Ada Desa --</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading desa:', error);
                            desaSelect.innerHTML = '<option value="">-- Error Loading Data --</option>';
                        });
                } else {
                    desaSelect.innerHTML = '<option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>';
                }
            }

            // Event listener untuk perubahan manual
            kecamatanSelect.addEventListener('change', function() {
                loadDesa(this.value);
            });

            // Handle old input (jika ada error validasi)
            const oldIdKec = '{{ old('id_kec') }}';
            const oldIdDesa = '{{ old('id_desa') }}';
            if (oldIdKec && oldIdDesa) {
                loadDesa(oldIdKec, oldIdDesa);
            }
        });
    </script>
@endsection
