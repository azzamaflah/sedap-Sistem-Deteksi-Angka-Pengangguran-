@extends('layouts.app')

@section('title', 'Tambah Responden')
@section('page-title', 'Tambah Data Responden')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('responden.index') }}">Responden</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('responden.store') }}" id="formResponden">
            @csrf

            <!-- SECTION: Identitas Wilayah -->
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Identitas Wilayah</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Kecamatan -->
                    <div class="col-md-6">
                        <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                        <select name="id_kec" id="kecamatan" class="form-select" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach ($kecamatan as $kec)
                                <option value="{{ $kec->id_kec }}">{{ $kec->nama_kec }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Desa -->
                    <div class="col-md-6">
                        <label class="form-label">Desa <span class="text-danger">*</span></label>
                        <select name="id_desa" id="desa" class="form-select" required disabled>
                            <option value="">-- Pilih Kecamatan Dulu --</option>
                        </select>
                    </div>

                    <!-- Blok Sensus -->
                    <div class="col-md-4">
                        <label class="form-label">Blok Sensus <span class="text-danger">*</span></label>
                        <select name="id_bs" id="blok_sensus" class="form-select" required disabled>
                            <option value="">-- Pilih Desa Dulu --</option>
                        </select>
                    </div>

                    <!-- NKS -->
                    <div class="col-md-4">
                        <label class="form-label">Nomor Kode Sample (NKS)</label>
                        <select name="id_nks" id="nks" class="form-select" disabled>
                            <option value="">-- Pilih Blok Sensus Dulu --</option>
                        </select>
                    </div>

                    <!-- Nomor Urut RT -->
                    <div class="col-md-4">
                        <label class="form-label">Nomor Urut Rumah Tangga <span class="text-danger">*</span></label>
                        <select name="id_nurt" id="nurt" class="form-select" required disabled>
                            <option value="">-- Pilih NKS Dulu --</option>
                        </select>
                    </div>

                    <!-- Nama Sample -->
                    <div class="col-md-12">
                        <label class="form-label">Nama Responden <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sample" id="nama_sample" class="form-control"
                            placeholder="Masukkan nama responden" required>
                    </div>
                </div>
            </div>

            <!-- SECTION: Kuesioner Ketenagakerjaan -->
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Kuesioner Ketenagakerjaan</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">

                    <!-- QUEST 1 (r7_1) -->
                    <div class="col-md-12">
                        <div class="alert alert-light border">
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
                        </div>
                    </div>

                    <!-- QUEST 2 (r7_2) -->
                    <div class="col-md-12" id="quest2_wrapper" style="display:none;">
                        <div class="alert alert-light border">
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

                    <!-- QUEST 3 (r7_3) -->
                    <div class="col-md-12" id="quest3_wrapper" style="display:none;">
                        <div class="alert alert-light border">
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

                    <!-- QUEST 4 (r8_1) -->
                    <div class="col-md-12" id="quest4_wrapper" style="display:none;">
                        <div class="alert alert-light border">
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

                    <!-- QUEST 5 (r9_1) - HANYA UNTUK BEKERJA -->
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

                    <!-- QUEST 6 (r9_3) - HANYA UNTUK BEKERJA -->
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

                    <!-- QUEST 7 (r20_1) - HANYA UNTUK PENGANGGURAN -->
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

                    <!-- QUEST 8 (r20_2) - HANYA UNTUK PENGANGGURAN -->
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

                    <!-- QUEST 9 (r20_4) - HANYA UNTUK PENGANGGURAN -->
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

            <!-- Footer -->
            <div class="card-footer text-end">
                <a href="{{ route('responden.index') }}" class="btn btn-secondary">
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
            // ===== DROPDOWN DINAMIS =====

            // 1. Kecamatan -> Desa
            document.getElementById('kecamatan').addEventListener('change', function() {
                const idKec = this.value;
                const desaSelect = document.getElementById('desa');
                const blokSensusSelect = document.getElementById('blok_sensus');
                const nksSelect = document.getElementById('nks');
                const nurtSelect = document.getElementById('nurt');

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
                const blokSensusSelect = document.getElementById('blok_sensus');
                const nksSelect = document.getElementById('nks');
                const nurtSelect = document.getElementById('nurt');

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
                const nksSelect = document.getElementById('nks');
                const nurtSelect = document.getElementById('nurt');

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
                const nurtSelect = document.getElementById('nurt');

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

            // Fungsi: Tampilkan Quest 5-6 DAN Quest 7 (BEKERJA)
            function showBekerjaQuests() {
                // Show Quest 5-6 (detail pekerjaan)
                document.getElementById('quest5_wrapper').style.display = 'block';
                document.getElementById('quest6_wrapper').style.display = 'block';
                r9_1.disabled = false;
                r9_3.disabled = false;

                // ✅ PERBAIKAN: Show Quest 7 juga (untuk yang bekerja tapi mau cari tambahan)
                document.getElementById('quest7_wrapper').style.display = 'block';
                r20_1.disabled = false;

                // Quest 8 & 9 masih hidden, menunggu jawaban Quest 7
                document.getElementById('quest8_wrapper').style.display = 'none';
                document.getElementById('quest9_wrapper').style.display = 'none';
                r20_2.disabled = true;
                r20_2.value = '';
                r20_4.disabled = true;
                r20_4.value = '';
            }

            // Fungsi: Tampilkan Quest 7 saja (PENGANGGURAN)
            function showPengangguranQuests() {
                // Hide Quest 5-6
                document.getElementById('quest5_wrapper').style.display = 'none';
                document.getElementById('quest6_wrapper').style.display = 'none';
                r9_1.disabled = true;
                r9_1.value = '';
                r9_3.disabled = true;
                r9_3.value = '';

                // Show Quest 7
                document.getElementById('quest7_wrapper').style.display = 'block';
                r20_1.disabled = false;

                // Quest 8 & 9 masih hidden, menunggu jawaban Quest 7
                document.getElementById('quest8_wrapper').style.display = 'none';
                document.getElementById('quest9_wrapper').style.display = 'none';
                r20_2.disabled = true;
                r20_2.value = '';
                r20_4.disabled = true;
                r20_4.value = '';
            }

            // ===== QUEST 1 LOGIC =====
            r7_1.addEventListener('change', function() {
                // Reset Quest 2-9 display dan disable
                ['quest2_wrapper', 'quest3_wrapper', 'quest4_wrapper', 'quest5_wrapper',
                    'quest6_wrapper', 'quest7_wrapper', 'quest8_wrapper', 'quest9_wrapper'
                ].forEach(id => {
                    document.getElementById(id).style.display = 'none';
                });

                // Reset fields
                r7_2.disabled = true;
                r7_2.value = '';
                r7_3.disabled = true;
                r7_3.value = '';
                r8_1.disabled = true;
                r8_1.value = '';
                r9_1.disabled = true;
                r9_1.value = '';
                r9_3.disabled = true;
                r9_3.value = '';
                r20_1.disabled = true;
                r20_1.value = '';
                r20_2.disabled = true;
                r20_2.value = '';
                r20_4.disabled = true;
                r20_4.value = '';

                if (this.value === 'Ya') {
                    // BEKERJA - Skip Quest 2-4, show Quest 5-6 DAN Quest 7
                    showBekerjaQuests();
                } else if (this.value === 'Tidak') {
                    // Lanjut ke Quest 2
                    document.getElementById('quest2_wrapper').style.display = 'block';
                    r7_2.disabled = false;
                }
            });

            // ===== QUEST 2 LOGIC =====
            r7_2.addEventListener('change', function() {
                // Reset Quest 3-9
                ['quest3_wrapper', 'quest4_wrapper', 'quest5_wrapper',
                    'quest6_wrapper', 'quest7_wrapper', 'quest8_wrapper', 'quest9_wrapper'
                ].forEach(id => {
                    document.getElementById(id).style.display = 'none';
                });

                r7_3.disabled = true;
                r7_3.value = '';
                r8_1.disabled = true;
                r8_1.value = '';
                r9_1.disabled = true;
                r9_1.value = '';
                r9_3.disabled = true;
                r9_3.value = '';
                r20_1.disabled = true;
                r20_1.value = '';
                r20_2.disabled = true;
                r20_2.value = '';
                r20_4.disabled = true;
                r20_4.value = '';

                if (this.value === 'Ya') {
                    // BEKERJA - Skip Quest 3-4, show Quest 5-6 DAN Quest 7
                    showBekerjaQuests();
                } else if (this.value === 'Tidak') {
                    // Lanjut ke Quest 3
                    document.getElementById('quest3_wrapper').style.display = 'block';
                    r7_3.disabled = false;
                }
            });

            // ===== QUEST 3 LOGIC =====
            r7_3.addEventListener('change', function() {
                // Reset Quest 4-9
                ['quest4_wrapper', 'quest5_wrapper', 'quest6_wrapper',
                    'quest7_wrapper', 'quest8_wrapper', 'quest9_wrapper'
                ].forEach(id => {
                    document.getElementById(id).style.display = 'none';
                });

                r8_1.disabled = true;
                r8_1.value = '';
                r9_1.disabled = true;
                r9_1.value = '';
                r9_3.disabled = true;
                r9_3.value = '';
                r20_1.disabled = true;
                r20_1.value = '';
                r20_2.disabled = true;
                r20_2.value = '';
                r20_4.disabled = true;
                r20_4.value = '';

                if (this.value === 'Ya') {
                    // BEKERJA - Skip Quest 4, show Quest 5-6 DAN Quest 7
                    showBekerjaQuests();
                } else if (this.value === 'Tidak') {
                    // Lanjut ke Quest 4
                    document.getElementById('quest4_wrapper').style.display = 'block';
                    r8_1.disabled = false;
                }
            });

            // ===== QUEST 4 LOGIC =====
            r8_1.addEventListener('change', function() {
                // Reset Quest 5-9
                ['quest5_wrapper', 'quest6_wrapper', 'quest7_wrapper',
                    'quest8_wrapper', 'quest9_wrapper'
                ].forEach(id => {
                    document.getElementById(id).style.display = 'none';
                });

                r9_1.disabled = true;
                r9_1.value = '';
                r9_3.disabled = true;
                r9_3.value = '';
                r20_1.disabled = true;
                r20_1.value = '';
                r20_2.disabled = true;
                r20_2.value = '';
                r20_4.disabled = true;
                r20_4.value = '';

                if (this.value === 'Ya') {
                    // BEKERJA - show Quest 5-6 DAN Quest 7
                    showBekerjaQuests();
                } else if (this.value === 'Tidak') {
                    // PENGANGGURAN - Quest 1-4 semua "Tidak", show Quest 7
                    showPengangguranQuests();
                }
            });

            // ===== QUEST 7 LOGIC (r20_1) =====
            r20_1.addEventListener('change', function() {
                // Reset Quest 8 & 9
                document.getElementById('quest8_wrapper').style.display = 'none';
                document.getElementById('quest9_wrapper').style.display = 'none';
                r20_2.disabled = true;
                r20_2.value = '';
                r20_4.disabled = true;
                r20_4.value = '';

                // Tampilkan Quest 8 setelah Quest 7 dijawab (Ya atau Tidak)
                if (this.value === 'Ya' || this.value === 'Tidak') {
                    document.getElementById('quest8_wrapper').style.display = 'block';
                    r20_2.disabled = false;
                }
            });

            // ===== QUEST 8 LOGIC (r20_2) =====
            r20_2.addEventListener('change', function() {
                const q7Value = r20_1.value;
                const q8Value = this.value;

                // Reset Quest 9
                document.getElementById('quest9_wrapper').style.display = 'none';
                r20_4.disabled = true;
                r20_4.value = '';

                // LOGIC QUEST 9:
                // 1. Jika Q7=Ya DAN Q8=Ya → Q9 TIDAK tampil
                // 2. Jika Q7=Ya DAN Q8=Tidak → Q9 tampil
                // 3. Jika Q7=Tidak DAN Q8=Tidak → Q9 tampil
                // 4. Jika Q7=Tidak DAN Q8=Ya → Q9 tampil

                if (q7Value === 'Ya' && q8Value === 'Ya') {
                    // Quest 9 TIDAK tampil (sudah dapat pekerjaan dan mempersiapkan usaha)
                    document.getElementById('quest9_wrapper').style.display = 'none';
                    r20_4.disabled = true;
                    r20_4.value = '';
                } else {
                    // Selain kondisi di atas, Quest 9 tampil
                    document.getElementById('quest9_wrapper').style.display = 'block';
                    r20_4.disabled = false;
                }
            });

        });
    </script>
@endsection
