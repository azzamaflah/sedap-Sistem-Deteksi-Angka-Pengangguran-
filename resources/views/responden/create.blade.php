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
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .quest-group {
            background: white;
            padding: 15px;
            border-left: 4px solid #007bff;
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
    </style>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4>➕ Tambah Data Responden</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('responden.store') }}" method="POST" id="formResponden">
                            @csrf

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
                                                <option value="{{ $kec->id_kec }}">{{ $kec->id_kec }} -
                                                    {{ $kec->nama_kec }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_kec')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Desa <span class="text-danger">*</span></label>
                                        <select name="id_desa" id="id_desa"
                                            class="form-select @error('id_desa') is-invalid @enderror" required disabled>
                                            <option value="">-- Pilih Desa --</option>
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
                                            class="form-select @error('id_bs') is-invalid @enderror" required disabled>
                                            <option value="">-- Pilih BS --</option>
                                        </select>
                                        @error('id_bs')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">NKS <span class="text-danger">*</span></label>
                                        <select name="id_nks" id="id_nks"
                                            class="form-select @error('id_nks') is-invalid @enderror" required disabled>
                                            <option value="">-- Pilih NKS --</option>
                                        </select>
                                        @error('id_nks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">NURT <span class="text-danger">*</span></label>
                                        <select name="id_nurt" id="id_nurt"
                                            class="form-select @error('id_nurt') is-invalid @enderror" required disabled>
                                            <option value="">-- Pilih NURT --</option>
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
                                        value="{{ old('nama_sample') }}" required>
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
                                                                {{ old($quest->key) == $option ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $quest->key }}_{{ $option }}">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif($quest->type === 'text')
                                                <input type="text" name="{{ $quest->key }}"
                                                    class="form-control quest-input" value="{{ old($quest->key) }}"
                                                    placeholder="Masukkan {{ strtolower($quest->label) }}">
                                            @elseif($quest->type === 'textarea')
                                                <textarea name="{{ $quest->key }}" class="form-control quest-input" rows="3"
                                                    placeholder="Masukkan {{ strtolower($quest->label) }}">{{ old($quest->key) }}</textarea>
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
                                <button type="submit" class="btn btn-primary">
                                    💾 Simpan Data
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
            // Cascade Dropdown Logic
            const idKec = document.getElementById('id_kec');
            const idDesa = document.getElementById('id_desa');
            const idBs = document.getElementById('id_bs');
            const idNks = document.getElementById('id_nks');
            const idNurt = document.getElementById('id_nurt');

            // Load Desa based on Kecamatan
            idKec.addEventListener('change', function() {
                const kecId = this.value;
                idDesa.innerHTML = '<option value="">-- Pilih Desa --</option>';
                idBs.innerHTML = '<option value="">-- Pilih BS --</option>';
                idNks.innerHTML = '<option value="">-- Pilih NKS --</option>';
                idNurt.innerHTML = '<option value="">-- Pilih NURT --</option>';

                idDesa.disabled = true;
                idBs.disabled = true;
                idNks.disabled = true;
                idNurt.disabled = true;

                if (kecId) {
                    fetch(`/responden/get-desa/${kecId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(desa => {
                                idDesa.innerHTML +=
                                    `<option value="${desa.id_desa}">${desa.id_desa} - ${desa.nama_desa}</option>`;
                            });
                            idDesa.disabled = false;
                        });
                }
            });

            // Load Blok Sensus based on Desa
            idDesa.addEventListener('change', function() {
                const desaId = this.value;
                idBs.innerHTML = '<option value="">-- Pilih BS --</option>';
                idNks.innerHTML = '<option value="">-- Pilih NKS --</option>';
                idNurt.innerHTML = '<option value="">-- Pilih NURT --</option>';

                idBs.disabled = true;
                idNks.disabled = true;
                idNurt.disabled = true;

                if (desaId) {
                    fetch(`/responden/get-blok-sensus/${desaId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(bs => {
                                idBs.innerHTML +=
                                    `<option value="${bs.id_bs}">${bs.id_bs}</option>`;
                            });
                            idBs.disabled = false;
                        });
                }
            });

            // Load NKS based on Blok Sensus
            idBs.addEventListener('change', function() {
                const bsId = this.value;
                idNks.innerHTML = '<option value="">-- Pilih NKS --</option>';
                idNurt.innerHTML = '<option value="">-- Pilih NURT --</option>';

                idNks.disabled = true;
                idNurt.disabled = true;

                if (bsId) {
                    fetch(`/responden/get-nks/${bsId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(nks => {
                                idNks.innerHTML +=
                                    `<option value="${nks.id_nks}">${nks.id_nks}</option>`;
                            });
                            idNks.disabled = false;
                        });
                }
            });

            // Load NURT based on NKS
            idNks.addEventListener('change', function() {
                const nksId = this.value;
                idNurt.innerHTML = '<option value="">-- Pilih NURT --</option>';
                idNurt.disabled = true;

                if (nksId) {
                    fetch(`/responden/get-nurt/${nksId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(nurt => {
                                idNurt.innerHTML +=
                                    `<option value="${nurt.id_nurt}">${nurt.id_nurt}</option>`;
                            });
                            idNurt.disabled = false;
                        });
                }
            });

            // Dynamic Quest Logic (misal: hide/show quest berdasarkan jawaban)
            const questInputs = document.querySelectorAll('.quest-input');
            questInputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Contoh: Jika ada logic conditional quest, bisa ditambahkan di sini
                    console.log(`Quest ${this.name} changed to: ${this.value}`);
                });
            });
        });
    </script>
@endsection
