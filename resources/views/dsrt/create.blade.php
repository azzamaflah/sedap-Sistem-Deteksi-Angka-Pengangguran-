@extends('layouts.app')

@section('title', 'Tambah Sampel Rumah Tangga')
@section('page-title', 'Tambah Sampel Rumah Tangga')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dsrt.index') }}">Sampel Rumah Tangga</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('dsrt.store') }}">
            @csrf
            <div class="card-body row g-3">

                <!-- Dropdown Kecamatan -->
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

                <!-- Dropdown Desa (Dynamic) -->
                <div class="col-md-4">
                    <label class="form-label">Desa <span class="text-danger">*</span></label>
                    <select name="id_desa" id="desa" class="form-select" required disabled>
                        <option value="">-- Pilih Kecamatan Dulu --</option>
                    </select>
                    @error('id_desa')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dropdown Blok Sensus (Dynamic) -->
                <div class="col-md-4">
                    <label class="form-label">Blok Sensus <span class="text-danger">*</span></label>
                    <select name="id_bs" id="blok_sensus" class="form-select" required disabled>
                        <option value="">-- Pilih Desa Dulu --</option>
                    </select>
                    @error('id_bs')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dropdown Nomor Kode Sample (Dynamic) -->
                <div class="col-md-4">
                    <label class="form-label">Nomor Kode Sample (NKS)</label>
                    <select name="id_nks" id="nks" class="form-select" disabled>
                        <option value="">-- Pilih Blok Sensus Dulu --</option>
                    </select>
                    @error('id_nks')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dropdown Nomor Urut Rumah Tangga (1-10) -->
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

                <!-- Dropdown Hasil Pencacahan (GANTI NAME JADI 'respon') -->
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

            <div class="card-footer text-end">
                <a href="{{ route('dsrt.index') }}" class="btn btn-secondary">
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
        // 1. Kecamatan -> Desa
        document.getElementById('kecamatan').addEventListener('change', function() {
            const idKec = this.value;
            const desaSelect = document.getElementById('desa');
            const blokSensusSelect = document.getElementById('blok_sensus');
            const nksSelect = document.getElementById('nks');

            desaSelect.innerHTML = '<option value="">-- Loading... --</option>';
            desaSelect.disabled = true;
            blokSensusSelect.innerHTML = '<option value="">-- Pilih Desa Dulu --</option>';
            blokSensusSelect.disabled = true;
            nksSelect.innerHTML = '<option value="">-- Pilih Blok Sensus Dulu --</option>';
            nksSelect.disabled = true;

            if (idKec) {
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
        document.getElementById('desa').addEventListener('change', function() {
            const idDesa = this.value;
            const blokSensusSelect = document.getElementById('blok_sensus');
            const nksSelect = document.getElementById('nks');

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
        document.getElementById('blok_sensus').addEventListener('change', function() {
            const idBs = this.value;
            const nksSelect = document.getElementById('nks');

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
    </script>
@endsection
