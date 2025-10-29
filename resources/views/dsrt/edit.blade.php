@extends('layouts.app')

@section('title', 'Edit Sampel Rumah Tangga')
@section('page-title', 'Edit Sampel Rumah Tangga')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dsrt.index') }}">Sampel Rumah Tangga</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('dsrt.update', $dsrt->no) }}">
            @csrf
            @method('PUT')
            <div class="card-body row g-3">

                <!-- Dropdown Kecamatan -->
                <div class="col-md-4">
                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                    <select name="id_kec" id="kecamatan" class="form-select" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach ($kecamatan as $kec)
                            <option value="{{ $kec->id_kec }}" {{ $dsrt->id_kec == $kec->id_kec ? 'selected' : '' }}>
                                {{ $kec->nama_kec }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Desa -->
                <div class="col-md-4">
                    <label class="form-label">Desa <span class="text-danger">*</span></label>
                    <select name="id_desa" id="desa" class="form-select" required>
                        <option value="">-- Pilih Desa --</option>
                        @foreach ($desa as $ds)
                            <option value="{{ $ds->id_desa }}" {{ $dsrt->id_desa == $ds->id_desa ? 'selected' : '' }}>
                                {{ $ds->nama_desa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Blok Sensus -->
                <div class="col-md-4">
                    <label class="form-label">Blok Sensus <span class="text-danger">*</span></label>
                    <select name="id_bs" id="blok_sensus" class="form-select" required>
                        <option value="">-- Pilih Blok Sensus --</option>
                        @foreach ($blokSensus as $bs)
                            <option value="{{ $bs->id_bs }}" {{ $dsrt->id_bs == $bs->id_bs ? 'selected' : '' }}>
                                {{ $bs->id_bs }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown NKS -->
                <div class="col-md-4">
                    <label class="form-label">Nomor Kode Sample (NKS)</label>
                    <select name="id_nks" id="nks" class="form-select">
                        <option value="">-- Pilih NKS --</option>
                        @foreach ($nks as $n)
                            <option value="{{ $n->id_nks }}" {{ $dsrt->id_nks == $n->id_nks ? 'selected' : '' }}>
                                {{ $n->id_nks }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Nomor Urut RT -->
                <div class="col-md-4">
                    <label class="form-label">Nomor Urut Rumah Tangga <span class="text-danger">*</span></label>
                    <select name="id_nurt" class="form-select" required>
                        <option value="">-- Pilih Nomor Urut --</option>
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ $dsrt->id_nurt == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Dropdown Hasil Pencacahan -->
                <div class="col-md-4">
                    <label class="form-label">Hasil Pencacahan <span class="text-danger">*</span></label>
                    <select name="respon" class="form-select" required>
                        <option value="">-- Pilih Hasil --</option>
                        <option value="Respon" {{ $dsrt->respon == 'Respon' ? 'selected' : '' }}>Respon</option>
                        <option value="Non Respon" {{ $dsrt->respon == 'Non Respon' ? 'selected' : '' }}>Non Respon
                        </option>
                    </select>
                </div>

            </div>

            <div class="card-footer text-end">
                <a href="{{ route('dsrt.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
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
            blokSensusSelect.innerHTML = '<option value="">-- Pilih Desa Dulu --</option>';
            nksSelect.innerHTML = '<option value="">-- Pilih Blok Sensus Dulu --</option>';

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
                        } else {
                            desaSelect.innerHTML = '<option value="">-- Tidak Ada Desa --</option>';
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

            blokSensusSelect.innerHTML = '<option value="">-- Loading... --</option>';
            nksSelect.innerHTML = '<option value="">-- Pilih Blok Sensus Dulu --</option>';

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
                        } else {
                            nksSelect.innerHTML = '<option value="">-- Tidak Ada NKS --</option>';
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>
@endsection
