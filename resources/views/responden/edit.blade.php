@extends('layouts.app')

@section('title', 'Tambah Responden')
@section('page-title', 'Tambah Data Responden')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('responden.index') }}">Responden</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Form Tambah Responden</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('responden.store') }}" method="POST" id="formResponden">
                @csrf

                <!-- Data Wilayah (sama seperti sebelumnya) -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                        <select name="id_kec" id="id_kec" class="form-select" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach ($kecamatan as $kec)
                                <option value="{{ $kec->id_kec }}">{{ $kec->nama_kec }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Desa <span class="text-danger">*</span></label>
                        <select name="id_desa" id="id_desa" class="form-select" required>
                            <option value="">-- Pilih Desa --</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Blok Sensus <span class="text-danger">*</span></label>
                        <select name="id_bs" id="id_bs" class="form-select" required>
                            <option value="">-- Pilih BS --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">NKS <span class="text-danger">*</span></label>
                        <select name="id_nks" id="id_nks" class="form-select" required>
                            <option value="">-- Pilih NKS --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">NURT <span class="text-danger">*</span></label>
                        <select name="id_nurt" id="id_nurt" class="form-select" required>
                            <option value="">-- Pilih NURT --</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Sample <span class="text-danger">*</span></label>
                    <input type="text" name="nama_sample" class="form-control" required>
                </div>

                <hr class="my-4">

                <!-- Quest 1-4 (Status Bekerja) -->
                <h5 class="mb-3"><i class="bi bi-clipboard-check"></i> Status Pekerjaan</h5>

                <div class="card mb-3 border-primary">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Quest 1: Apakah memiliki pekerjaan? <span
                                    class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input quest-1-4" type="radio" name="r7_1" value="Ya"
                                        required>
                                    <label class="form-check-label">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input quest-1-4" type="radio" name="r7_1" value="Tidak"
                                        required>
                                    <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Quest 2: Apakah bekerja sementara? <span
                                    class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input quest-1-4" type="radio" name="r7_2" value="Ya"
                                        required>
                                    <label class="form-check-label">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input quest-1-4" type="radio" name="r7_2" value="Tidak"
                                        required>
                                    <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Quest 3: Apakah memiliki usaha sendiri? <span
                                    class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input quest-1-4" type="radio" name="r7_3" value="Ya"
                                        required>
                                    <label class="form-check-label">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input quest-1-4" type="radio" name="r7_3"
                                        value="Tidak" required>
                                    <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Quest 4: Apakah pekerja keluarga? <span
                                    class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input quest-1-4" type="radio" name="r8_1"
                                        value="Ya" required>
                                    <label class="form-check-label">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input quest-1-4" type="radio" name="r8_1"
                                        value="Tidak" required>
                                    <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quest 5-6 (Hanya muncul jika BEKERJA) -->
                <div id="quest-5-6" style="display: none;">
                    <h5 class="mb-3"><i class="bi bi-briefcase"></i> Detail Pekerjaan (Bekerja)</h5>
                    <div class="card mb-3 border-success">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Quest 5: Lapangan usaha</label>
                                <input type="text" name="r9_1" class="form-control"
                                    placeholder="Contoh: Pertanian, Perdagangan, dll">
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold">Quest 6: Jenis pekerjaan</label>
                                <input type="text" name="r9_3" class="form-control"
                                    placeholder="Contoh: Petani, Pedagang, dll">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quest 7-9 (Pengangguran - CONDITIONAL) -->
                <div id="quest-7-9" style="display: none;">
                    <h5 class="mb-3"><i class="bi bi-search"></i> Pencarian Pekerjaan</h5>
                    <div class="card mb-3 border-warning">
                        <div class="card-body">
                            <!-- Quest 7 -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Quest 7: Apakah mencari pekerjaan?</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="r20_1" id="r20_1_ya"
                                            value="Ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="r20_1" id="r20_1_tidak"
                                            value="Tidak">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Quest 8 (Muncul setelah Quest 7 dijawab) -->
                            <div class="mb-3" id="quest-8-container" style="display: none;">
                                <label class="form-label fw-bold">Quest 8: Apakah mempersiapkan usaha?</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="r20_2" id="r20_2_ya"
                                            value="Ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="r20_2" id="r20_2_tidak"
                                            value="Tidak">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Quest 9 (Conditional berdasarkan Quest 7 & 8) -->
                            <div class="mb-0" id="quest-9-container" style="display: none;">
                                <label class="form-label fw-bold">Quest 9: Alasan tidak mencari pekerjaan</label>
                                <textarea name="r20_4" class="form-control" rows="3" placeholder="Jelaskan alasan..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('responden.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // AJAX untuk dropdown cascade (sama seperti sebelumnya)
        // ... kode AJAX kecamatan, desa, bs, nks, nurt ...

        // ========== LOGIC QUEST 1-9 ==========

        // Fungsi cek apakah ada yang jawab Ya di Quest 1-4
        function checkQuest14() {
            const q1 = document.querySelector('input[name="r7_1"]:checked');
            const q2 = document.querySelector('input[name="r7_2"]:checked');
            const q3 = document.querySelector('input[name="r7_3"]:checked');
            const q4 = document.querySelector('input[name="r8_1"]:checked');

            let adaYa = false;
            let semuaTidak = true;

            if (q1 && q1.value === 'Ya') adaYa = true;
            if (q2 && q2.value === 'Ya') adaYa = true;
            if (q3 && q3.value === 'Ya') adaYa = true;
            if (q4 && q4.value === 'Ya') adaYa = true;

            if (!q1 || q1.value !== 'Tidak') semuaTidak = false;
            if (!q2 || q2.value !== 'Tidak') semuaTidak = false;
            if (!q3 || q3.value !== 'Tidak') semuaTidak = false;
            if (!q4 || q4.value !== 'Tidak') semuaTidak = false;

            // Jika ada yang Ya → BEKERJA → tampilkan Quest 5-6 dan Quest 7-9
            if (adaYa) {
                document.getElementById('quest-5-6').style.display = 'block';
                document.getElementById('quest-7-9').style.display = 'block';
            } else {
                document.getElementById('quest-5-6').style.display = 'none';

                // Jika semua Tidak → PENGANGGURAN → tampilkan Quest 7-9
                if (semuaTidak) {
                    document.getElementById('quest-7-9').style.display = 'block';
                } else {
                    document.getElementById('quest-7-9').style.display = 'none';
                }
            }

            // Reset Quest 7-9
            resetQuest789();
        }

        // Event listener Quest 1-4
        document.querySelectorAll('.quest-1-4').forEach(radio => {
            radio.addEventListener('change', checkQuest14);
        });

        // ========== LOGIC QUEST 7-9 ==========

        // Reset Quest 7-9
        function resetQuest789() {
            document.querySelectorAll('input[name="r20_1"]').forEach(r => r.checked = false);
            document.querySelectorAll('input[name="r20_2"]').forEach(r => r.checked = false);
            document.querySelector('textarea[name="r20_4"]').value = '';
            document.getElementById('quest-8-container').style.display = 'none';
            document.getElementById('quest-9-container').style.display = 'none';
        }

        // Quest 7: Ketika dijawab, tampilkan Quest 8
        document.querySelectorAll('input[name="r20_1"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Tampilkan Quest 8
                document.getElementById('quest-8-container').style.display = 'block';

                // Reset Quest 8 & 9
                document.querySelectorAll('input[name="r20_2"]').forEach(r => r.checked = false);
                document.querySelector('textarea[name="r20_4"]').value = '';
                document.getElementById('quest-9-container').style.display = 'none';
            });
        });

        // Quest 8: Logic untuk Quest 9
        document.querySelectorAll('input[name="r20_2"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const q7 = document.querySelector('input[name="r20_1"]:checked');
                const q8 = this.value;

                // Logic:
                // - Jika Q7=Ya DAN Q8=Ya → Q9 TIDAK tampil
                // - Jika Q7=Ya DAN Q8=Tidak → Q9 tampil
                // - Jika Q7=Tidak DAN Q8=Tidak → Q9 tampil
                // - Jika Q7=Tidak DAN Q8=Ya → Q9 tampil (opsional, tergantung aturan)

                if (q7.value === 'Ya' && q8 === 'Ya') {
                    // Quest 9 TIDAK tampil
                    document.getElementById('quest-9-container').style.display = 'none';
                    document.querySelector('textarea[name="r20_4"]').value = '';
                } else {
                    // Quest 9 tampil
                    document.getElementById('quest-9-container').style.display = 'block';
                }
            });
        });
    </script>
@endsection
