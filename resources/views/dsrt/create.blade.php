@extends('layouts.app')

@section('title', 'Tambah Sampel Rumah Tangga')
@section('page-title', 'Tambah Sampel Rumah Tangga')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dsrt.index') }}">Sampel Rumah Tangga</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('styles')
    <style>
        /* Card Header Enhancement - Digunakan pada layout utama tapi diduplikasi untuk konsistensi jika card di-render cepat */
        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 2px solid #f0f0f0 !important;
            padding-top: 1.2rem;
            padding-bottom: 1.2rem;
        }

        /* Smooth Page Load Animation (Untuk div.fade-in) */
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
        
        /* Mengaplikasikan style pada card form */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Card Footer Styling */
        .card-footer {
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

        .card-footer .btn-primary {
            /* Pastikan warna teks putih dan latar belakang biru yang solid */
            color: white !important;
            background-color: #007bff !important; 
            opacity: 1 !important; /* Memastikan opacity penuh */
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2) !important;
        }

        .card-footer .btn-primary:hover {
            background-color: #0056b3 !important; 
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm fade-in">
        <form method="POST" action="{{ route('dsrt.store') }}">
            @csrf

            {{-- HEADER CARD DENGAN JUDUL YANG LEBIH JELAS --}}
            <div class="card-header">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-person-fill-add me-2"></i> Pengisian Data Sampel Rumah Tangga Baru
                </h5>
            </div>
            
            <div class="card-body row g-4"> {{-- Menggunakan g-4 untuk jarak antar baris yang lebih lega --}}
                
                {{-- Bagian 1: Informasi Wilayah (3 Kolom) --}}
                <div class="col-12">
                    <h6 class="form-group-title">
                        <i class="bi bi-geo-alt-fill me-1"></i> Informasi Lokasi
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <select name="id_kec" id="kecamatan" class="form-select" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($kecamatan as $kec)
                                    <option value="{{ $kec->id_kec }}">{{ $kec->nama_kec }}</option>
                                @endforeach
                            </select>
                            @error('id_kec')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Desa <span class="text-danger">*</span></label>
                            <select name="id_desa" id="desa" class="form-select" required disabled>
                                <option value="">-- Pilih Kecamatan Dulu --</option>
                            </select>
                            @error('id_desa')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Blok Sensus <span class="text-danger">*</span></label>
                            <select name="id_bs" id="blok_sensus" class="form-select" required disabled>
                                <option value="">-- Pilih Desa Dulu --</option>
                            </select>
                            @error('id_bs')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Horizontal Separator --}}
                <div class="col-12">
                    <hr class="mt-0 mb-0">
                </div>

                {{-- Bagian 2: Informasi Sampel dan Hasil (3 Kolom) --}}
                <div class="col-12">
                    <h6 class="form-group-title">
                        <i class="bi bi-card-checklist me-1"></i> Detail Sampel
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nomor Kode Sample (NKS)</label>
                            <select name="id_nks" id="nks" class="form-select" disabled>
                                <option value="">-- Pilih Blok Sensus Dulu --</option>
                            </select>
                            @error('id_nks')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nomor Urut Rumah Tangga <span class="text-danger">*</span></label>
                            <select name="id_nurt" class="form-select" required>
                                <option value="">-- Pilih Nomor Urut --</option>
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('id_nurt')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Hasil Pencacahan <span class="text-danger">*</span></label>
                            <select name="respon" class="form-select" required>
                                <option value="">-- Pilih Hasil --</option>
                                <option value="Respon">Respon</option>
                                <option value="Non Respon">Non Respon</option>
                            </select>
                            @error('respon')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('dsrt.index') }}" class="btn btn-secondary me-2">
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

            // Observe all fade-in elements
            document.querySelectorAll('.fade-in').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.6s ease-out';
                observer.observe(el);
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

            // --- Logika Dropdown Dinamis ---

            // 1. Kecamatan -> Desa
            const kecamatanSelect = document.getElementById('kecamatan');
            const desaSelect = document.getElementById('desa');
            const blokSensusSelect = document.getElementById('blok_sensus');
            const nksSelect = document.getElementById('nks');

            function resetDesaBlokNKS() {
                desaSelect.innerHTML = '<option value="">-- Pilih Kecamatan Dulu --</option>';
                desaSelect.disabled = true;
                blokSensusSelect.innerHTML = '<option value="">-- Pilih Desa Dulu --</option>';
                blokSensusSelect.disabled = true;
                nksSelect.innerHTML = '<option value="">-- Pilih Blok Sensus Dulu --</option>';
                nksSelect.disabled = true;
            }

            kecamatanSelect.addEventListener('change', function() {
                const idKec = this.value;
                resetDesaBlokNKS();

                if (idKec) {
                    desaSelect.innerHTML = '<option value="">-- Loading... --</option>';
                    fetch(`/api/dsrt/desa/${idKec}`)
                        .then(response => response.json())
                        .then(data => {
                            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
                            if (data.length > 0) {
                                data.forEach(desa => {
                                    const option = document.createElement('option');
                                    option.value = desa.id_desa;
                                    option.textContent = desa.nama_desa;
                                    desaSelect.appendChild(option);
                                });
                                desaSelect.disabled = false;
                            } else {
                                desaSelect.innerHTML = '<option value="">-- Tidak Ada Desa --</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            desaSelect.innerHTML = '<option value="">-- Error Loading --</option>';
                        });
                }
            });

            // 2. Desa -> Blok Sensus
            desaSelect.addEventListener('change', function() {
                const idDesa = this.value;
                blokSensusSelect.innerHTML = '<option value="">-- Loading... --</option>';
                blokSensusSelect.disabled = true;
                nksSelect.innerHTML = '<option value="">-- Pilih Blok Sensus Dulu --</option>';
                nksSelect.disabled = true;

                if (idDesa) {
                    fetch(`/api/dsrt/bloksensus/${idDesa}`)
                        .then(response => response.json())
                        .then(data => {
                            blokSensusSelect.innerHTML = '<option value="">-- Pilih Blok Sensus --</option>';
                            if (data.length > 0) {
                                data.forEach(bs => {
                                    const option = document.createElement('option');
                                    option.value = bs.id_bs;
                                    option.textContent = bs.id_bs;
                                    blokSensusSelect.appendChild(option);
                                });
                                blokSensusSelect.disabled = false;
                            } else {
                                blokSensusSelect.innerHTML =
                                '<option value="">-- Tidak Ada Blok Sensus --</option>';
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // 3. Blok Sensus -> NKS
            blokSensusSelect.addEventListener('change', function() {
                const idBs = this.value;
                nksSelect.innerHTML = '<option value="">-- Loading... --</option>';
                nksSelect.disabled = true;

                if (idBs) {
                    fetch(`/api/dsrt/nks/${idBs}`)
                        .then(response => response.json())
                        .then(data => {
                            nksSelect.innerHTML = '<option value="">-- Pilih NKS (Opsional) --</option>';
                            if (data.length > 0) {
                                data.forEach(nks => {
                                    const option = document.createElement('option');
                                    option.value = nks.id_nks;
                                    option.textContent = nks.id_nks;
                                    nksSelect.appendChild(option);
                                });
                                nksSelect.disabled = false;
                            } else {
                                nksSelect.innerHTML = '<option value="">-- Tidak Ada NKS --</option>';
                                nksSelect.disabled = false;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>
@endsection