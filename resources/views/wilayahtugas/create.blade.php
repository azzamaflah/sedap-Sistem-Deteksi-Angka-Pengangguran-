@extends('layouts.app')

@section('title', 'Tambah Wilayah Tugas')

@section('page-title', 'Tambah Wilayah Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('wilayahTugas.index') }}">Wilayah Tugas</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('wilayahTugas.store') }}">
            @csrf
            <div class="card-body row g-3">
                <!-- Dropdown Kecamatan -->
                <div class="col-md-6">
                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                    <select name="id_kec" id="kecamatan" class="form-select" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach ($kecamatan as $kec)
                            <option value="{{ $kec->id_kec }}" {{ old('id_kec') == $kec->id_kec ? 'selected' : '' }}>
                                {{ $kec->nama_kec }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kec')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dropdown Desa (Dynamic) -->
                <div class="col-md-6">
                    <label class="form-label">Desa <span class="text-danger">*</span></label>
                    <select name="id_desa" id="desa" class="form-select" required disabled>
                        <option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>
                    </select>
                    @error('id_desa')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input Blok Sensus -->
                <div class="col-md-4">
                    <label class="form-label">Blok Sensus (ID BS) <span class="text-danger">*</span></label>
                    <input type="text" name="id_bs" class="form-control" placeholder="Contoh: 0001"
                        value="{{ old('id_bs') }}" required>
                    @error('id_bs')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input Nomor Kode Sample -->
                <div class="col-md-4">
                    <label class="form-label">Nomor Kode Sample (ID NKS)</label>
                    <input type="text" name="id_nks" class="form-control" placeholder="Contoh: 000101"
                        value="{{ old('id_nks') }}">
                    @error('id_nks')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ✅ PERBAIKAN: Dropdown Nama Pengawas -->
                <div class="col-md-4">
                    <label class="form-label">Nama Pengawas <span class="text-danger">*</span></label>
                    <select name="id_user" class="form-select" required>
                        <option value="">-- Pilih Pengawas --</option>
                        @foreach ($pengawas as $user)
                            {{-- ✅ FIXED: Pakai $user->id dan $user->name (bukan id_user dan nama) --}}
                            <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_user')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('wilayahTugas.index') }}" class="btn btn-secondary">
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
        // Dynamic Dropdown: Kecamatan -> Desa
        document.getElementById('kecamatan').addEventListener('change', function() {
            const idKec = this.value;
            const desaSelect = document.getElementById('desa');

            // Reset dropdown desa
            desaSelect.innerHTML = '<option value="">-- Loading... --</option>';
            desaSelect.disabled = true;

            if (idKec) {
                // Fetch desa berdasarkan kecamatan
                fetch(`/api/desa/${idKec}`)
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
                        desaSelect.innerHTML = '<option value="">-- Error Loading Data --</option>';
                    });
            } else {
                desaSelect.innerHTML = '<option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>';
            }
        });
    </script>
@endsection
