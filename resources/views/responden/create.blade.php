@extends('layouts.app')

@section('title', 'Tambah Responden')
@section('page-title', 'Tambah Data Responden')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('responden.index') }}">Responden</a></li>
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

        /* PERBAIKAN TOMBOL SIMPAN (Konsisten dengan DSRT.create) */
        .card-footer .btn-primary {
            color: white !important;
            background-color: #007bff !important; 
            opacity: 1 !important; 
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2) !important;
        }

        .card-footer .btn-primary:hover {
            background-color: #0056b3 !important; 
        }
        
        /* Style untuk Header Section di dalam Form (Override default card-header) */
        .card .header-section {
            padding: 1.25rem;
            color: white;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        /* Style untuk Alert Kuesioner */
        .alert-light-border {
            background-color: #f8f9fa;
            border-left: 5px solid #ccc !important; /* Tambahkan border vertikal */
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm fade-in">
        <form method="POST" action="{{ route('responden.store') }}" id="formResponden">
            @csrf

            {{-- SECTION: Identitas Wilayah --}}
            {{-- Mengganti card-header default dengan div yang bisa di-style --}}
            <div class="header-section bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i> Identitas Wilayah</h5>
            </div>
            
            <div class="card-body">
                <div class="row g-3">
                    {{-- Baris 1 --}}
                    <div class="col-md-6">
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

                    <div class="col-md-6">
                        <label class="form-label">Desa <span class="text-danger">*</span></label>
                        <select name="id_desa" id="desa" class="form-select" required disabled>
                            <option value="">-- Pilih Kecamatan Dulu --</option>
                        </select>
                        @error('id_desa')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Baris 2 (G-3) --}}
                    <div class="col-md-4">
                        <label class="form-label">Blok Sensus <span class="text-danger">*</span></label>
                        <select name="id_bs" id="blok_sensus" class="form-select" required disabled>
                            <option value="">-- Pilih Desa Dulu --</option>
                        </select>
                        @error('id_bs')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

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
                        <select name="id_nurt" id="nurt" class="form-select" required disabled>
                            <option value="">-- Pilih NKS Dulu --</option>
                        </select>
                        @error('id_nurt')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Nama Responden <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sample" id="nama_sample" class="form-control"
                            placeholder="Masukkan nama responden" required>
                        @error('nama_sample')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION: Kuesioner Ketenagakerjaan --}}
            <div class="header-section bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i> Kuesioner Ketenagakerjaan</h5>
            </div>
            
            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-12">
                        <div class="alert alert-light-border border">
                            <label class="form-label fw-bold">
                                Quest 1: Dalam seminggu terakhir, apakah <span id="nama_quest_1"
                                    class="text-primary">(NAMA)</span> bekerja untuk memperoleh bayaran/upah/gaji yang
                                dilakukan paling sedikit satu jam?
                                <span class="text-danger">*</span>
                            </label>
                            <select name="r7_1" id="r7_1" class="form-select" required>
                                <option value="">-- Pilih Jawaban --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                            @error('r7_1')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12" id="quest2_wrapper" style="display:none;">
                        <div class="alert alert-light-border border">
                            <label class="form-label fw-bold">
                                Quest 2: Dalam seminggu terakhir, apakah <span id="nama_quest_2"
                                    class="text-primary">(NAMA)</span> menjalankan usaha, bertani atau melakukan kegiatan
                                lainnya untuk memperoleh keuntungan/pendapatan yang dilakukan paling sedikit satu jam?
                            </label>
                            <select name="r7_2" id="r7_2" class="form-select" disabled>
                                <option value="">-- Pilih Jawaban --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12" id="quest3_wrapper" style="display:none;">
                        <div class="alert alert-light-border border">
                            <label class="form-label fw-bold">
                                Quest 3: Dalam seminggu terakhir, apakah <span id="nama_quest_3"
                                    class="text-primary">(NAMA)</span> membantu kegiatan usaha atau pekerjaan
                                keluarga/kerabat lainnya yang dilakukan paling sedikit satu jam?
                            </label>
                            <select name="r7_3" id="r7_3" class="form-select" disabled>
                                <option value="">-- Pilih Jawaban --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12" id="quest4_wrapper" style="display:none;">
                        <div class="alert alert-light-border border">
                            <label class="form-label fw-bold">
                                Quest 4: Apakah <span id="nama_quest_4" class="text-primary">(NAMA)</span> sebenarnya
                                memiliki pekerjaan/kegiatan usaha, tetapi seminggu terakhir sedang tidak bekerja/tidak
                                menjalankan usaha tersebut?
                            </label>
                            <select name="r8_1" id="r8_1" class="form-select" disabled>
                                <option value="">-- Pilih Jawaban --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12" id="quest5_wrapper" style="display:none;">
                        <div class="alert alert-info border">
                            <label class="form-label fw-bold">
                                Quest 5: Di bidang apakah pekerjaan utama <span id="nama_quest_5"
                                    class="text-primary">(NAMA)</span>?
                            </label>
                            <select name="r9_1" id="r9_1" class="form-select" disabled>
                                <option value="">-- Pilih Bidang Pekerjaan --</option>
                                <option value="Pertanian tanaman pangan">Pertanian tanaman pangan (padi, jagung, kedelai,
                                    gandum, singkong/ubi kayu, ubi jalar, talas, gadung, dll)</option>
                                <option value="Pertanian Bukan Tanaman Pangan">Pertanian Bukan Tanaman Pangan</option>
                                <option value="Pemeliharaan Binatang Ternak">Pemeliharaan Binatang Ternak</option>
                                <option value="Perikanan">Perikanan</option>
                                <option value="Pekerjaan lainnya">Pekerjaan utama tidak pada bidang yang disebutkan di atas
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12" id="quest6_wrapper" style="display:none;">
                        <div class="alert alert-info border">
                            <label class="form-label fw-bold">
                                Quest 6: Apakah barang/produk yang dihasilkan dari pekerjaan/kegiatan di bidang tersebut
                                digunakan untuk?
                            </label>
                            <select name="r9_3" id="r9_3" class="form-select" disabled>
                                <option value="">-- Pilih Penggunaan Produk --</option>
                                <option value="Seluruhnya untuk dijual">Seluruhnya untuk dijual</option>
                                <option value="Sebagian besar dijual">Sebagian besar dijual</option>
                                <option value="Sebagian besar dikonsumsi rumah tangga">Sebagian besar dikonsumsi rumah
                                    tangga</option>
                                <option value="Seluruhnya untuk dikonsumsi rumah tangga">Seluruhnya untuk dikonsumsi rumah
                                    tangga</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12" id="quest7_wrapper" style="display:none;">
                        <div class="alert alert-warning border">
                            <label class="form-label fw-bold">
                                Quest 7: Dalam seminggu terakhir, apakah <span id="nama_quest_7"
                                    class="text-primary">(NAMA)</span> mencari pekerjaan?
                            </label>
                            <select name="r20_1" id="r20_1" class="form-select" disabled>
                                <option value="">-- Pilih Jawaban --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12" id="quest8_wrapper" style="display:none;">
                        <div class="alert alert-warning border">
                            <label class="form-label fw-bold">
                                Quest 8: Dalam seminggu terakhir, apakah <span id="nama_quest_8"
                                    class="text-primary">(NAMA)</span> sedang mempersiapkan suatu kegiatan usaha yang baru?
                            </label>
                            <select name="r20_2" id="r20_2" class="form-select" disabled>
                                <option value="">-- Pilih Jawaban --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12" id="quest9_wrapper" style="display:none;">
                        <div class="alert alert-warning border">
                            <label class="form-label fw-bold">
                                Quest 9: Apakah alasan utama <span id="nama_quest_9" class="text-primary">(NAMA)</span>
                                tidak mencari pekerjaan dan memulai kegiatan usaha dalam rentang waktu seminggu terakhir?
                            </label>
                            <select name="r20_4" id="r20_4" class="form-select" disabled>
                                <option value="">-- Pilih Alasan --</option>
                                <option value="Sudah diterima bekerja tapi belum mulai bekerja">Sudah diterima bekerja tapi
                                    belum mulai bekerja</option>
                                <option value="Sudah mempunyai usaha tapi belum memulainya">Sudah mempunyai usaha tapi
                                    belum memulainya</option>
                                <option value="Putus asa">Putus asa (merasa tidak mungkin mendapatkan pekerjaan, kurangnya
                                    pengalaman kerja, ketidaksesuaian dengan keahlian yang dimiliki, dan dianggap terlalu
                                    muda atau terlalu tua oleh calon pemberi kerja/majikan)</option>
                                <option value="Sudah mempunyai pekerjaan/usaha">Sudah mempunyai pekerjaan/usaha</option>
                                <option value="Melakukan kegiatan lain">Melakukan kegiatan lain (mengurus rumah
                                    tangga/sekolah)</option>
                                <option value="Tidak mampu melakukan pekerjaan">Tidak mampu melakukan pekerjaan</option>
                                <option value="Selain alasan di atas">Selain alasan di atas</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="card-footer text-end">
                <a href="{{ route('responden.index') }}" class="btn btn-secondary me-2">
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


            // ===== DROPDOWN DINAMIS =====

            const desaSelect = document.getElementById('desa');
            const blokSensusSelect = document.getElementById('blok_sensus');
            const nksSelect = document.getElementById('nks');
            const nurtSelect = document.getElementById('nurt');

            // 1. Kecamatan -> Desa
            document.getElementById('kecamatan').addEventListener('change', function() {
                const idKec = this.value;
                
                // Reset semua turunan
                desaSelect.innerHTML = '<option value="">-- Loading... --</option>';
                desaSelect.disabled = true;
                blokSensusSelect.innerHTML = '<option value="">-- Pilih Desa Dulu --</option>';
                blokSensusSelect.disabled = true;
                nksSelect.innerHTML = '<option value="">-- Pilih Blok Sensus Dulu --</option>';
                nksSelect.disabled = true;
                nurtSelect.innerHTML = '<option value="">-- Pilih NKS Dulu --</option>';
                nurtSelect.disabled = true;

                if (idKec) {
                    fetch(`/api/responden/desa/${idKec}`)
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
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // 2. Desa -> Blok Sensus
            document.getElementById('desa').addEventListener('change', function() {
                const idDesa = this.value;

                // Reset turunan
                blokSensusSelect.innerHTML = '<option value="">-- Loading... --</option>';
                blokSensusSelect.disabled = true;
                nksSelect.innerHTML = '<option value="">-- Pilih Blok Sensus Dulu --</option>';
                nksSelect.disabled = true;
                nurtSelect.innerHTML = '<option value="">-- Pilih NKS Dulu --</option>';
                nurtSelect.disabled = true;

                if (idDesa) {
                    fetch(`/api/responden/bloksensus/${idDesa}`)
                        .then(response => response.json())
                        .then(data => {
                            blokSensusSelect.innerHTML =
                                '<option value="">-- Pilih Blok Sensus --</option>';
                            if (data.length > 0) {
                                data.forEach(bs => {
                                    const option = document.createElement('option');
                                    option.value = bs.id_bs;
                                    option.textContent = bs.id_bs;
                                    blokSensusSelect.appendChild(option);
                                });
                                blokSensusSelect.disabled = false;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // 3. Blok Sensus -> NKS
            document.getElementById('blok_sensus').addEventListener('change', function() {
                const idBs = this.value;

                // Reset turunan
                nksSelect.innerHTML = '<option value="">-- Loading... --</option>';
                nksSelect.disabled = true;
                nurtSelect.innerHTML = '<option value="">-- Pilih NKS Dulu --</option>';
                nurtSelect.disabled = true;

                if (idBs) {
                    fetch(`/api/responden/nks/${idBs}`)
                        .then(response => response.json())
                        .then(data => {
                            nksSelect.innerHTML = '<option value="">-- Pilih NKS --</option>';
                            if (data.length > 0) {
                                data.forEach(nks => {
                                    const option = document.createElement('option');
                                    option.value = nks.id_nks;
                                    option.textContent = nks.id_nks;
                                    nksSelect.appendChild(option);
                                });
                                nksSelect.disabled = false;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // 4. NKS -> Nurt
            document.getElementById('nks').addEventListener('change', function() {
                const idNks = this.value;

                // Reset turunan
                nurtSelect.innerHTML = '<option value="">-- Loading... --</option>';
                nurtSelect.disabled = true;

                if (idNks) {
                    fetch(`/api/responden/nurt/${idNks}`)
                        .then(response => response.json())
                        .then(data => {
                            nurtSelect.innerHTML =
                                '<option value="">-- Pilih Nomor Urut RT --</option>';
                            if (data.length > 0) {
                                data.forEach(nurt => {
                                    const option = document.createElement('option');
                                    option.value = nurt.id_nurt;
                                    option.textContent = nurt.id_nurt;
                                    nurtSelect.appendChild(option);
                                });
                                nurtSelect.disabled = false;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // ===== UPDATE NAMA DI PERTANYAAN =====
            const namaSampleInput = document.getElementById('nama_sample');
            if (namaSampleInput) {
                namaSampleInput.addEventListener('input', function() {
                    const nama = this.value || '(NAMA)';
                    document.querySelectorAll('[id^="nama_quest_"]').forEach(el => {
                        el.textContent = nama;
                    });
                });
            }

            // ===== LOGIKA KUESIONER =====
            const r7_1 = document.getElementById('r7_1');
            const r7_2 = document.getElementById('r7_2');
            const r7_3 = document.getElementById('r7_3');
            const r8_1 = document.getElementById('r8_1');
            const r9_1 = document.getElementById('r9_1');
            const r9_3 = document.getElementById('r9_3');
            const r20_1 = document.getElementById('r20_1');
            const r20_2 = document.getElementById('r20_2');
            const r20_4 = document.getElementById('r20_4');

            function resetAllQuests() {
                ['quest2_wrapper', 'quest3_wrapper', 'quest4_wrapper', 'quest5_wrapper',
                    'quest6_wrapper', 'quest7_wrapper', 'quest8_wrapper', 'quest9_wrapper'
                ].forEach(id => {
                    document.getElementById(id).style.display = 'none';
                });

                r7_2.disabled = true; r7_2.value = '';
                r7_3.disabled = true; r7_3.value = '';
                r8_1.disabled = true; r8_1.value = '';
                r9_1.disabled = true; r9_1.value = '';
                r9_3.disabled = true; r9_3.value = '';
                r20_1.disabled = true; r20_1.value = '';
                r20_2.disabled = true; r20_2.value = '';
                r20_4.disabled = true; r20_4.value = '';
            }

            function showBekerjaQuests() {
                // Show Quest 5-6 (detail pekerjaan) dan Quest 7 (mencari tambahan)
                document.getElementById('quest5_wrapper').style.display = 'block';
                document.getElementById('quest6_wrapper').style.display = 'block';
                document.getElementById('quest7_wrapper').style.display = 'block';
                r9_1.disabled = false;
                r9_3.disabled = false;
                r20_1.disabled = false;

                // Pastikan Quest 8 & 9 di-reset dan disembunyikan
                document.getElementById('quest8_wrapper').style.display = 'none';
                document.getElementById('quest9_wrapper').style.display = 'none';
                r20_2.disabled = true; r20_2.value = '';
                r20_4.disabled = true; r20_4.value = '';
            }

            function showPengangguranQuests() {
                // Hide Quest 5-6
                document.getElementById('quest5_wrapper').style.display = 'none';
                document.getElementById('quest6_wrapper').style.display = 'none';
                r9_1.disabled = true; r9_1.value = '';
                r9_3.disabled = true; r9_3.value = '';

                // Show Quest 7
                document.getElementById('quest7_wrapper').style.display = 'block';
                r20_1.disabled = false;

                // Quest 8 & 9 masih hidden, menunggu jawaban Quest 7
                document.getElementById('quest8_wrapper').style.display = 'none';
                document.getElementById('quest9_wrapper').style.display = 'none';
                r20_2.disabled = true; r20_2.value = '';
                r20_4.disabled = true; r20_4.value = '';
            }
            
            // QUEST 1 LOGIC
            r7_1.addEventListener('change', function() {
                resetAllQuests();

                if (this.value === 'Ya') {
                    showBekerjaQuests();
                } else if (this.value === 'Tidak') {
                    document.getElementById('quest2_wrapper').style.display = 'block';
                    r7_2.disabled = false;
                }
            });

            // QUEST 2 LOGIC
            r7_2.addEventListener('change', function() {
                resetAllQuests();
                document.getElementById('quest2_wrapper').style.display = 'block'; // Keep Q2 visible

                if (this.value === 'Ya') {
                    showBekerjaQuests();
                } else if (this.value === 'Tidak') {
                    document.getElementById('quest3_wrapper').style.display = 'block';
                    r7_3.disabled = false;
                }
            });

            // QUEST 3 LOGIC
            r7_3.addEventListener('change', function() {
                resetAllQuests();
                document.getElementById('quest2_wrapper').style.display = 'block'; // Keep Q2 visible
                document.getElementById('quest3_wrapper').style.display = 'block'; // Keep Q3 visible

                if (this.value === 'Ya') {
                    showBekerjaQuests();
                } else if (this.value === 'Tidak') {
                    document.getElementById('quest4_wrapper').style.display = 'block';
                    r8_1.disabled = false;
                }
            });

            // QUEST 4 LOGIC
            r8_1.addEventListener('change', function() {
                resetAllQuests();
                document.getElementById('quest2_wrapper').style.display = 'block'; 
                document.getElementById('quest3_wrapper').style.display = 'block';
                document.getElementById('quest4_wrapper').style.display = 'block';

                if (this.value === 'Ya') {
                    showBekerjaQuests();
                } else if (this.value === 'Tidak') {
                    showPengangguranQuests();
                }
            });

            // QUEST 7 LOGIC (r20_1) - Mencari pekerjaan?
            r20_1.addEventListener('change', function() {
                // Reset Quest 8 & 9
                document.getElementById('quest8_wrapper').style.display = 'none';
                document.getElementById('quest9_wrapper').style.display = 'none';
                r20_2.disabled = true; r20_2.value = '';
                r20_4.disabled = true; r20_4.value = '';

                // Tampilkan Quest 8 setelah Quest 7 dijawab (Ya atau Tidak)
                if (this.value === 'Ya' || this.value === 'Tidak') {
                    document.getElementById('quest8_wrapper').style.display = 'block';
                    r20_2.disabled = false;
                }
            });

            // QUEST 8 LOGIC (r20_2) - Mempersiapkan usaha?
            r20_2.addEventListener('change', function() {
                const q7Value = r20_1.value;
                const q8Value = this.value;

                // Reset Quest 9
                document.getElementById('quest9_wrapper').style.display = 'none';
                r20_4.disabled = true; r20_4.value = '';

                // LOGIC QUEST 9 (Hanya tampil jika Q7=Ya AND Q8=Tidak) OR (Q7=Tidak AND Q8=Tidak) OR (Q7=Tidak AND Q8=Ya)
                if (q7Value === 'Ya' && q8Value === 'Ya') {
                    // Q9 TIDAK tampil (Sudah mencari pekerjaan dan sudah mempersiapkan usaha)
                    document.getElementById('quest9_wrapper').style.display = 'none';
                } else if (q7Value === 'Tidak' || q8Value === 'Tidak') {
                    // Q9 tampil (Kondisi lain di mana tidak ada pekerjaan/usaha yang pasti)
                    document.getElementById('quest9_wrapper').style.display = 'block';
                    r20_4.disabled = false;
                }
            });
        });
    </script>
@endsection