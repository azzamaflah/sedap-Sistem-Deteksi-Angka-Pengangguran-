@extends('layouts.app')

@section('content')
    <style>
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-section h5 {
            color: #495057;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .quest-group {
            background: white;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .quest-label {
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
        }

        .quest-description {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 10px;
            font-style: italic;
        }

        .status-badge {
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h4>✏️ Edit Data Responden</h4>
                    </div>

                    <div class="card-body">
                        <!-- Status Display -->
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Status Saat Ini:</strong>
                            </div>
                            <div>
                                @if ($responden->bekerja)
                                    <span class="status-badge bg-success text-white">✅ {{ $responden->bekerja }}</span>
                                @elseif($responden->pengangguran)
                                    <span class="status-badge bg-danger text-white">❌ {{ $responden->pengangguran }}</span>
                                @else
                                    <span class="status-badge bg-secondary text-white">⏳ Belum Ditentukan</span>
                                @endif
                            </div>
                        </div>

                        <form action="{{ route('responden.update', $responden->no) }}" method="POST" id="formResponden">
                            @csrf
                            @method('PUT')

                            <!-- Section 1: Identitas Wilayah -->
                            <div class="form-section">
                                <h5>📍 Identitas Wilayah</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                        <select name="id_kec" id="id_kec"
                                            class="form-select @error('id_kec') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kecamatan --</option>
                                            @foreach ($kecamatan as $kec)
                                                <option value="{{ $kec->id_kec }}"
                                                    {{ $responden->id_kec == $kec->id_kec ? 'selected' : '' }}>
                                                    {{ $kec->id_kec }} - {{ $kec->nama_kec }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_kec')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Desa <span class="text-danger">*</span></label>
                                        <select name="id_desa" id="id_desa"
                                            class="form-select @error('id_desa') is-invalid @enderror" required>
                                            <option value="">-- Pilih Desa --</option>
                                            @foreach ($desa as $d)
                                                <option value="{{ $d->id_desa }}"
                                                    {{ $responden->id_desa == $d->id_desa ? 'selected' : '' }}>
                                                    {{ $d->id_desa }} - {{ $d->nama_desa }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_desa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Blok Sensus <span class="text-danger">*</span></label>
                                        <select name="id_bs" id="id_bs"
                                            class="form-select @error('id_bs') is-invalid @enderror" required>
                                            <option value="">-- Pilih BS --</option>
                                            @foreach ($blokSensus as $bs)
                                                <option value="{{ $bs->id_bs }}"
                                                    {{ $responden->id_bs == $bs->id_bs ? 'selected' : '' }}>
                                                    {{ $bs->id_bs }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_bs')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">NKS <span class="text-danger">*</span></label>
                                        <select name="id_nks" id="id_nks"
                                            class="form-select @error('id_nks') is-invalid @enderror" required>
                                            <option value="">-- Pilih NKS --</option>
                                            @foreach ($nks as $n)
                                                <option value="{{ $n->id_nks }}"
                                                    {{ $responden->id_nks == $n->id_nks ? 'selected' : '' }}>
                                                    {{ $n->id_nks }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_nks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">NURT <span class="text-danger">*</span></label>
                                        <select name="id_nurt" id="id_nurt"
                                            class="form-select @error('id_nurt') is-invalid @enderror" required>
                                            <option value="">-- Pilih NURT --</option>
                                            @foreach ($nurt as $nu)
                                                <option value="{{ $nu->id_nurt }}"
                                                    {{ $responden->id_nurt == $nu->id_nurt ? 'selected' : '' }}>
                                                    {{ $nu->id_nurt }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_nurt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Sample <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_sample"
                                        class="form-control @error('nama_sample') is-invalid @enderror"
                                        value="{{ old('nama_sample', $responden->nama_sample) }}" required>
                                    @error('nama_sample')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section 2: Kuesioner Ketenagakerjaan (Dynamic) -->
                            <div class="form-section">
                                <h5>📋 Kuesioner Ketenagakerjaan</h5>

                                @foreach ($quests as $quest)
                                    @if ($quest->is_active)
                                        <div class="quest-group" id="quest-{{ $quest->key }}">
                                            <label class="quest-label">
                                                {{ $quest->label }}
                                            </label>

                                            @if ($quest->description)
                                                <div class="quest-description">
                                                    {{ $quest->description }}
                                                </div>
                                            @endif

                                            @if ($quest->type === 'radio')
                                                <div class="d-flex gap-3">
                                                    @foreach ($quest->getOptionsArray() as $option)
                                                        <div class="form-check">
                                                            <input class="form-check-input quest-input" type="radio"
                                                                name="{{ $quest->key }}"
                                                                id="{{ $quest->key }}_{{ $option }}"
                                                                value="{{ $option }}"
                                                                {{ old($quest->key, $responden->{$quest->key}) == $option ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $quest->key }}_{{ $option }}">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif($quest->type === 'text')
                                                <input type="text" name="{{ $quest->key }}"
                                                    class="form-control quest-input"
                                                    value="{{ old($quest->key, $responden->{$quest->key}) }}"
                                                    placeholder="Masukkan {{ strtolower($quest->label) }}">
                                            @elseif($quest->type === 'textarea')
                                                <textarea name="{{ $quest->key }}" class="form-control quest-input" rows="3"
                                                    placeholder="Masukkan {{ strtolower($quest->label) }}">{{ old($quest->key, $responden->{$quest->key}) }}</textarea>
                                            @endif

                                            @error($quest->key)
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('responden.index') }}" class="btn btn-secondary">
                                    ← Batal
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    💾 Update Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cascade Dropdown Logic (sama seperti create, tapi dengan pre-filled data)
            const idKec = document.getElementById('id_kec');
            const idDesa = document.getElementById('id_desa');
            const idBs = document.getElementById('id_bs');
            const idNks = document.getElementById('id_nks');
            const idNurt = document.getElementById('id_nurt');

            // Load Desa based on Kecamatan
            idKec.addEventListener('change', function() {
                const kecId = this.value;
                const currentDesa = '{{ $responden->id_desa }}';

                idDesa.innerHTML = '<option value="">-- Pilih Desa --</option>';

                if (kecId) {
                    fetch(`/responden/get-desa/${kecId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(desa => {
                                const selected = desa.id_desa == currentDesa ? 'selected' : '';
                                idDesa.innerHTML +=
                                    `<option value="${desa.id_desa}" ${selected}>${desa.id_desa} - ${desa.nama_desa}</option>`;
                            });
                        });
                }
            });

            // Load Blok Sensus based on Desa
            idDesa.addEventListener('change', function() {
                const desaId = this.value;
                const currentBs = '{{ $responden->id_bs }}';

                idBs.innerHTML = '<option value="">-- Pilih BS --</option>';

                if (desaId) {
                    fetch(`/responden/get-blok-sensus/${desaId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(bs => {
                                const selected = bs.id_bs == currentBs ? 'selected' : '';
                                idBs.innerHTML +=
                                    `<option value="${bs.id_bs}" ${selected}>${bs.id_bs}</option>`;
                            });
                        });
                }
            });

            // Load NKS based on Blok Sensus
            idBs.addEventListener('change', function() {
                const bsId = this.value;
                const currentNks = '{{ $responden->id_nks }}';

                idNks.innerHTML = '<option value="">-- Pilih NKS --</option>';

                if (bsId) {
                    fetch(`/responden/get-nks/${bsId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(nks => {
                                const selected = nks.id_nks == currentNks ? 'selected' : '';
                                idNks.innerHTML +=
                                    `<option value="${nks.id_nks}" ${selected}>${nks.id_nks}</option>`;
                            });
                        });
                }
            });

            // Load NURT based on NKS
            idNks.addEventListener('change', function() {
                const nksId = this.value;
                const currentNurt = '{{ $responden->id_nurt }}';

                idNurt.innerHTML = '<option value="">-- Pilih NURT --</option>';

                if (nksId) {
                    fetch(`/responden/get-nurt/${nksId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(nurt => {
                                const selected = nurt.id_nurt == currentNurt ? 'selected' : '';
                                idNurt.innerHTML +=
                                    `<option value="${nurt.id_nurt}" ${selected}>${nurt.id_nurt}</option>`;
                            });
                        });
                }
            });
        });
    </script>
@endsection
