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
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .quest-group.disabled-by-condition {
            opacity: 0.5;
            pointer-events: none;
            user-select: none;
            background: #fcfcfc;
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

                                {{-- --- DROPDOWN WILAYAH --- --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                        <select name="id_kec" id="id_kec"
                                            class="form-select @error('id_kec') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kecamatan --</option>
                                            @foreach ($kecamatan as $kec)
                                                <option value="{{ $kec->id_kec }}"
                                                    {{ ($responden->id_kec == $kec->id_kec || old('id_kec') == $kec->id_kec) ? 'selected' : '' }}>
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
                                            {{-- Desa Awal di-fill oleh Controller, Desa selanjutnya di-fill oleh JS --}}
                                            @foreach ($desa as $d)
                                                <option value="{{ $d->id_desa }}"
                                                    {{ ($responden->id_desa == $d->id_desa || old('id_desa') == $d->id_desa) ? 'selected' : '' }}>
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
                                                    {{ ($responden->id_bs == $bs->id_bs || old('id_bs') == $bs->id_bs) ? 'selected' : '' }}>
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
                                                    {{ ($responden->id_nks == $n->id_nks || old('id_nks') == $n->id_nks) ? 'selected' : '' }}>
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
                                                    {{ ($responden->id_nurt == $nu->id_nurt || old('id_nurt') == $nu->id_nurt) ? 'selected' : '' }}>
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
                                                            {{-- DITAMBAH: class quest-input-trigger --}}
                                                            <input class="form-check-input quest-input quest-input-trigger" type="radio"
                                                                name="{{ $quest->key }}"
                                                                id="{{ $quest->key }}_{{ $option }}"
                                                                value="{{ $option }}"
                                                                {{ (old($quest->key, $responden->{$quest->key}) == $option) ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $quest->key }}_{{ $option }}">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            
                                            @elseif ($quest->type === 'dropdown')
                                                {{-- DITAMBAH: class quest-input-trigger --}}
                                                <select name="{{ $quest->key }}" class="form-select quest-input quest-input-trigger" required>
                                                    <option value="">-- Pilih {{ $quest->label }} --</option>
                                                    @if(is_array($quest->options))
                                                        @foreach ($quest->options as $option)
                                                            <option value="{{ $option }}"
                                                                {{ (old($quest->key, $responden->{$quest->key}) == $option) ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            
                                            {{-- Tipe Text dan Textarea --}}
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


            // --- UTILITI CASCADE DROPDOWN ---

            function resetDropdown(element) {
                const placeholderName = element.id.replace('id_', '').toUpperCase();
                element.innerHTML = '<option value="">-- Pilih ' + placeholderName + ' --</option>';
                element.disabled = false; // Biarkan enable karena ini form edit, tapi hapus opsi non-default
            }

            // Mereset dropdown kaskade, dimulai dari elemen setelah 'startElement'
            function resetCascades(startElement = null) {
                const elements = [idDesa, idBs, idNks, idNurt];
                let shouldReset = false;

                elements.forEach(element => {
                    if (element === startElement) {
                        shouldReset = true; 
                    } else if (shouldReset) {
                        // Di form edit, jangan reset jika ada data lama yang harus dipertahankan.
                        // Tapi kita harus memastikan elemen yang 'lebih jauh' dari yang diubah direset.
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
                        // Simpan nilai yang saat ini dipilih (untuk form edit)
                        const currentValue = targetElement.value;
                        
                        // Bersihkan opsi lama (kecuali placeholder awal)
                        targetElement.innerHTML = '<option value="">-- Pilih ' + targetElement.id.replace('id_', '').toUpperCase() + ' --</option>';

                        data.forEach(item => {
                            const label = optionLabelKey ? `${item[optionValueKey]} - ${item[optionLabelKey]}` : item[optionValueKey];
                            const selected = item[optionValueKey] == currentValue ? 'selected' : '';
                            targetElement.innerHTML += `<option value="${item[optionValueKey]}" ${selected}>${label}</option>`;
                        });
                        targetElement.disabled = false;
                        return data;
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            }

            // Bindings Cascade Dropdown (Diperbaiki untuk Edit/Pre-fill)
            
            // NOTE: Di form edit, data Desa, BS, NKS, NURT awal sudah dimuat PHP. 
            // Kita hanya perlu memastikan cascade berfungsi jika pengguna MENGUBAH Kecamatan.

            idKec.addEventListener('change', function() {
                const kecId = this.value;
                resetCascades(idKec); 
                if (kecId) {
                    fetchData(`${baseUrl}/api/responden/desa/${kecId}`, idDesa, 'id_desa', 'nama_desa').then(() => {
                        // Dipaksa reset cascades lagi karena JS harus mengambil alih dari PHP
                        resetCascades(idKec); 
                        idDesa.dispatchEvent(new Event('change'));
                    });
                }
            });
            
            idDesa.addEventListener('change', function() {
                const desaId = this.value;
                resetCascades(idDesa);
                if (desaId) {
                    fetchData(`${baseUrl}/api/responden/bloksensus/${desaId}`, idBs, 'id_bs').then(() => {
                        idBs.dispatchEvent(new Event('change'));
                    });
                }
            });

            idBs.addEventListener('change', function() {
                const bsId = this.value;
                resetCascades(idBs);
                if (bsId) {
                    fetchData(`${baseUrl}/api/responden/nks/${bsId}`, idNks, 'id_nks').then(() => {
                        idNks.dispatchEvent(new Event('change'));
                    });
                }
            });

            idNks.addEventListener('change', function() {
                const nksId = this.value;
                resetCascades(idNks);
                if (nksId) {
                    fetchData(`${baseUrl}/api/responden/nurt/${nksId}`, idNurt, 'id_nurt');
                }
            });
            
            // --- LOGIC KUESIONER DINAMIS (CONDITIONAL LOGIC) ---
            
            function toggleQuestGroups(disableKeys) {
                // 1. Reset semua Quest ke status normal (enabled)
                allQuestGroups.forEach(group => {
                    group.classList.remove('disabled-by-condition');
                    group.querySelectorAll('.quest-input').forEach(input => {
                        input.removeAttribute('disabled');
                    });
                });
                
                // 2. Terapkan disable pada Quest yang ditargetkan
                disableKeys.forEach(keyToDisable => {
                    const targetGroup = document.getElementById(`quest-${keyToDisable}`);
                    if (targetGroup) {
                        targetGroup.classList.add('disabled-by-condition');
                        
                        targetGroup.querySelectorAll('.quest-input').forEach(input => {
                            input.setAttribute('disabled', 'disabled');
                            // Opsional: hapus nilai input yang dinonaktifkan
                            if (input.type === 'radio') {
                                input.checked = false;
                            } else if (input.tagName === 'SELECT') {
                                input.value = '';
                            } else { // Text / Textarea
                                input.value = '';
                            }
                        });
                    }
                });
            }

            function evaluateConditionals() {
                let keysToDisable = []; 
                
                allQuestGroups.forEach(group => {
                    const conditionalTargetString = group.getAttribute('data-conditional-target');
                    if (conditionalTargetString && conditionalTargetString !== '{}') {
                        
                        let currentAnswer = null;
                        const questKey = group.getAttribute('data-quest-key');
                        
                        // Cari jawaban saat ini
                        const radioChecked = group.querySelector('input[type="radio"]:checked');
                        const selectValue = group.querySelector('select')?.value;
                        
                        if (radioChecked) {
                            currentAnswer = radioChecked.value;
                        } else if (selectValue && selectValue !== "") {
                            currentAnswer = selectValue;
                        }
                        
                        if (currentAnswer) {
                            try {
                                const logic = JSON.parse(conditionalTargetString);
                                
                                if (logic[currentAnswer]) {
                                    keysToDisable = keysToDisable.concat(logic[currentAnswer]);
                                }
                            } catch (e) {
                                // console.error(`Error parsing conditional target for ${questKey}:`, e);
                            }
                        }
                    }
                });
                
                keysToDisable = [...new Set(keysToDisable)]; 
                toggleQuestGroups(keysToDisable);
            }

            // Bind event listener untuk Conditional Logic
            questInputs.forEach(input => {
                input.addEventListener('change', evaluateConditionals);
            });
            
            // Jalankan sekali saat load untuk menerapkan kondisi awal
            evaluateConditionals();

        });
    </script>
    @endsection
@endsection