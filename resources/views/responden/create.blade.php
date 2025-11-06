@extends('layouts.app')

@section('content')
    <style>
        /* CSS yang sudah ada */
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

        /* DITAMBAH: Style untuk menyembunyikan/menonaktifkan Quest secara visual */
        .quest-group.disabled-by-condition {
            opacity: 0.5;
            pointer-events: none;
            user-select: none;
            background: #fcfcfc;
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

                            <div class="form-section">
                                <h5>📍 Identitas Wilayah</h5>

                                {{-- ... Konten Identitas Wilayah (Kecamatan, Desa, BS, NKS, NURT) tetap sama ... --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                        <select name="id_kec" id="id_kec"
                                            class="form-select @error('id_kec') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kecamatan --</option>
                                            @foreach ($kecamatan as $kec)
                                                <option value="{{ $kec->id_kec }}"
                                                    {{ old('id_kec') == $kec->id_kec ? 'selected' : '' }}>
                                                    {{ $kec->id_kec }} -
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

                            <div class="form-section">
                                <h5>📋 Kuesioner Ketenagakerjaan</h5>

                                {{-- Looping untuk Quest --}}
                                @foreach ($quests as $quest)
                                    @if ($quest->is_active)
                                        {{-- DITAMBAH: data-conditional-target untuk JS --}}
                                        <div class="quest-group" id="quest-{{ $quest->key }}"
                                             data-quest-key="{{ $quest->key }}"
                                             data-conditional-target="{{ $quest->conditional_target ? json_encode($quest->conditional_target) : '{}' }}">
                                            
                                            <label class="quest-label">
                                                {{ $quest->label }}
                                            </label>

                                            @if ($quest->description)
                                                <div class="quest-description">
                                                    {{ $quest->description }}
                                                </div>
                                            @endif

                                            {{-- --- Rendering Input Dinamis --- --}}

                                            @if ($quest->type === 'radio')
                                                <div class="d-flex gap-3">
                                                    @foreach ($quest->options as $option)
                                                        <div class="form-check">
                                                            {{-- DITAMBAH: class quest-input-trigger untuk event listener --}}
                                                            <input class="form-check-input quest-input quest-input-trigger" type="radio"
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
                                            
                                            {{-- Menambahkan Tipe Dropdown --}}
                                            @elseif ($quest->type === 'dropdown')
                                                {{-- DITAMBAH: class quest-input-trigger untuk event listener --}}
                                                <select name="{{ $quest->key }}" class="form-select quest-input quest-input-trigger" required>
                                                    <option value="">-- Pilih {{ $quest->label }} --</option>
                                                    @if(is_array($quest->options))
                                                        @foreach ($quest->options as $option)
                                                            <option value="{{ $option }}"
                                                                {{ old($quest->key) == $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            
                                            {{-- Tipe Text dan Textarea tetap --}}
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

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- VARIABEL DAN SETUP ---
            const idKec = document.getElementById('id_kec');
            const idDesa = document.getElementById('id_desa');
            const idBs = document.getElementById('id_bs');
            const idNks = document.getElementById('id_nks');
            const idNurt = document.getElementById('id_nurt');
            const baseUrl = window.SEDAP ? window.SEDAP.baseUrl : ''; 
            const questInputs = document.querySelectorAll('.quest-input-trigger');
            const allQuestGroups = document.querySelectorAll('.quest-group');


            // --- UTILITI CASCADE DROPDOWN (Sama seperti sebelumnya, sudah diperbaiki) ---

            function resetDropdown(element) {
                const placeholderName = element.id.replace('id_', '').toUpperCase();
                element.innerHTML = '<option value="">-- Pilih ' + placeholderName + ' --</option>';
                element.disabled = true;
            }

            function resetCascades(startElement = null) {
                const elements = [idDesa, idBs, idNks, idNurt];
                let shouldReset = false;

                elements.forEach(element => {
                    if (element === startElement) {
                        shouldReset = true; 
                    } else if (shouldReset || startElement === null) {
                        resetDropdown(element);
                    }
                });
            }

            function fetchData(url, targetElement, optionValueKey, optionLabelKey = null) {
                return fetch(url)
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok: ' + res.statusText);
                        return res.json();
                    })
                    .then(data => {
                        data.forEach(item => {
                            const label = optionLabelKey ? `${item[optionValueKey]} - ${item[optionLabelKey]}` : item[optionValueKey];
                            targetElement.innerHTML += `<option value="${item[optionValueKey]}">${label}</option>`;
                        });
                        targetElement.disabled = false;
                        return data;
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            }

            // Bindings Cascade Dropdown (Sama seperti sebelumnya)
            idKec.addEventListener('change', function() {
                const kecId = this.value;
                resetCascades(idKec); 
                if (kecId) {
                    fetchData(`${baseUrl}/api/responden/desa/${kecId}`, idDesa, 'id_desa', 'nama_desa').then(() => {
                        const oldIdDesa = '{{ old('id_desa') }}';
                        if (oldIdDesa && idDesa.querySelector(`option[value="${oldIdDesa}"]`)) {
                            idDesa.value = oldIdDesa;
                            idDesa.dispatchEvent(new Event('change'));
                        }
                    });
                }
            });
            idDesa.addEventListener('change', function() {
                const desaId = this.value;
                resetCascades(idDesa);
                if (desaId) {
                    fetchData(`${baseUrl}/api/responden/bloksensus/${desaId}`, idBs, 'id_bs').then(() => {
                        const oldIdBs = '{{ old('id_bs') }}';
                        if (oldIdBs && idBs.querySelector(`option[value="${oldIdBs}"]`)) {
                            idBs.value = oldIdBs;
                            idBs.dispatchEvent(new Event('change'));
                        }
                    });
                }
            });
            idBs.addEventListener('change', function() {
                const bsId = this.value;
                resetCascades(idBs);
                if (bsId) {
                    fetchData(`${baseUrl}/api/responden/nks/${bsId}`, idNks, 'id_nks').then(() => {
                        const oldIdNks = '{{ old('id_nks') }}';
                        if (oldIdNks && idNks.querySelector(`option[value="${oldIdNks}"]`)) {
                            idNks.value = oldIdNks;
                            idNks.dispatchEvent(new Event('change'));
                        }
                    });
                }
            });
            idNks.addEventListener('change', function() {
                const nksId = this.value;
                resetCascades(idNks);
                if (nksId) {
                    fetchData(`${baseUrl}/api/responden/nurt/${nksId}`, idNurt, 'id_nurt').then(() => {
                        const oldIdNurt = '{{ old('id_nurt') }}';
                        if (oldIdNurt && idNurt.querySelector(`option[value="${oldIdNurt}"]`)) {
                            idNurt.value = oldIdNurt;
                        }
                    });
                }
            });

            // Inisialisasi Cascade
            const oldIdKec = '{{ old('id_kec') }}';
            if (oldIdKec) {
                idKec.value = oldIdKec;
                idKec.dispatchEvent(new Event('change'));
            }


            // --- LOGIC KUESIONER DINAMIS (CONDITIONAL LOGIC) ---
            
            /**
             * Menonaktifkan/mengaktifkan Quest group berdasarkan aturan kondisional.
             * @param {string[]} disableKeys Array of quest keys (e.g., ['r7_2', 'r7_3']) to disable.
             */
            function toggleQuestGroups(disableKeys) {
                // 1. Reset semua Quest ke status normal (enabled)
                allQuestGroups.forEach(group => {
                    group.classList.remove('disabled-by-condition');
                    // Hapus atribut disabled dari semua input di dalamnya
                    group.querySelectorAll('.quest-input').forEach(input => {
                        input.removeAttribute('disabled');
                    });
                });
                
                // 2. Terapkan disable pada Quest yang ditargetkan
                disableKeys.forEach(keyToDisable => {
                    const targetGroup = document.getElementById(`quest-${keyToDisable}`);
                    if (targetGroup) {
                        targetGroup.classList.add('disabled-by-condition');
                        // Tambahkan atribut disabled ke semua input di dalamnya
                        targetGroup.querySelectorAll('.quest-input').forEach(input => {
                            input.setAttribute('disabled', 'disabled');
                            // Opsional: hapus nilai input yang dinonaktifkan
                            if (input.type === 'radio' || input.tagName === 'SELECT') {
                                input.checked = false;
                                input.value = '';
                            } else {
                                input.value = '';
                            }
                        });
                    }
                });
            }

            /**
             * Menjalankan evaluasi kondisional setelah perubahan pada input.
             */
            function evaluateConditionals() {
                let keysToDisable = []; // Quest keys yang harus dinonaktifkan
                
                allQuestGroups.forEach(group => {
                    const conditionalTargetString = group.getAttribute('data-conditional-target');
                    if (conditionalTargetString && conditionalTargetString !== '{}') {
                        
                        let currentAnswer = null;
                        const questKey = group.getAttribute('data-quest-key');
                        
                        // Cari jawaban saat ini untuk Quest ini
                        if (group.querySelector('input[type="radio"]:checked')) {
                            currentAnswer = group.querySelector('input[type="radio"]:checked').value;
                        } else if (group.querySelector('select')) {
                            currentAnswer = group.querySelector('select').value;
                        }
                        
                        if (currentAnswer) {
                            try {
                                const logic = JSON.parse(conditionalTargetString);
                                
                                // Cek apakah jawaban saat ini ($currentAnswer) ada di aturan logika
                                if (logic[currentAnswer]) {
                                    // Tambahkan semua target ke daftar yang harus dinonaktifkan
                                    keysToDisable = keysToDisable.concat(logic[currentAnswer]);
                                }
                            } catch (e) {
                                console.error(`Error parsing conditional target for ${questKey}:`, e);
                            }
                        }
                    }
                });
                
                // Hapus duplikat dan jalankan toggle
                keysToDisable = [...new Set(keysToDisable)]; 
                toggleQuestGroups(keysToDisable);
            }

            // Bind event listener untuk Conditional Logic
            questInputs.forEach(input => {
                input.addEventListener('change', evaluateConditionals);
            });
            
            // Jalankan sekali saat load untuk menerapkan old value atau kondisi awal
            evaluateConditionals();

        });
    </script>
    @endsection
@endsection