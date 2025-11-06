@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">⚙️ Konfigurasi Quest Responden</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        Terjadi kesalahan:
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Pertanyaan (Quest)</h5>
                        <small class="text-muted">Ubah label dan konfigurasi pertanyaan yang ditampilkan di form
                            responden</small>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">Order</th>
                                    <th width="15%">Key</th>
                                    <th width="35%">Label Pertanyaan</th>
                                    <th width="10%">Type</th>
                                    <th width="10%">Options</th>
                                    <th width="10%">Kondisi</th>
                                    <th width="5%">Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quests as $quest)
                                    <tr>
                                        <form action="{{ route('admin.config.quest.update', $quest->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <td>
                                                <input type="number" name="order" class="form-control form-control-sm"
                                                    value="{{ $quest->order }}" required>
                                            </td>
                                            <td>
                                                <strong>{{ $quest->key }}</strong>
                                            </td>
                                            <td>
                                                <input type="text" name="label" class="form-control form-control-sm"
                                                    value="{{ $quest->label }}" required>
                                                <small><input type="text" name="description"
                                                        class="form-control form-control-sm mt-1"
                                                        placeholder="Deskripsi (opsional)"
                                                        value="{{ $quest->description }}"></small>
                                            </td>
                                            <td>
                                                <select name="type" class="form-select form-select-sm quest-type-select" required>
                                                    <option value="radio" {{ $quest->type == 'radio' ? 'selected' : '' }}>
                                                        Radio</option>
                                                    <option value="dropdown" {{ $quest->type == 'dropdown' ? 'selected' : '' }}>
                                                        Dropdown</option>
                                                    <option value="text" {{ $quest->type == 'text' ? 'selected' : '' }}>
                                                        Text</option>
                                                    <option value="textarea"
                                                        {{ $quest->type == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                                </select>
                                            </td>
                                            {{-- Kolom Edit Options (Modal Opsi) --}}
                                            <td>
                                                <input type="hidden" name="options" class="form-control form-control-sm options-input"
                                                    value="{{ is_array($quest->options) ? implode(', ', $quest->options) : '' }}">
                                                
                                                <button type="button" class="btn btn-sm btn-info btn-options" 
                                                        data-bs-toggle="modal" data-bs-target="#optionsModal"
                                                        data-current-options="{{ is_array($quest->options) ? implode(', ', $quest->options) : '' }}"
                                                        data-quest-label="{{ $quest->label }}">
                                                    ⚙️ Edit Opsi
                                                </button>
                                            </td>

                                            {{-- Kolom Atur Kondisi (Modal Kondisi) --}}
                                            <td>
                                                <input type="hidden" name="conditional_target" class="conditional-input"
                                                    value="{{ $quest->conditional_target ? json_encode($quest->conditional_target) : '' }}">
                                                
                                                <button type="button" class="btn btn-sm btn-warning btn-conditional" 
                                                        data-bs-toggle="modal" data-bs-target="#conditionalModal"
                                                        data-quest-id="{{ $quest->id }}"
                                                        data-quest-key="{{ $quest->key }}"
                                                        data-quest-label="{{ $quest->label }}"
                                                        data-options="{{ is_array($quest->options) ? implode(',', $quest->options) : '' }}"
                                                        data-targets='@json($quests->pluck('key', 'id')->toArray())'
                                                        data-current-logic="{{ $quest->conditional_target ? json_encode($quest->conditional_target) : '{}' }}">
                                                    🎯 Atur Kondisi
                                                </button>
                                            </td>

                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="is_active" value="0"> 
                                                    
                                                    <input class="form-check-input" type="checkbox" name="is_active"
                                                        value="1" 
                                                        id="is_active_{{ $quest->id }}" 
                                                        {{ $quest->is_active ? 'checked' : '' }}>
                                                    
                                                    <label class="form-check-label" for="is_active_{{ $quest->id }}">
                                                        {{ $quest->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-primary">💾 Update</button>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('admin.config.rumus') }}" class="btn btn-warning">🧮 Kelola Rumus Status</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">← Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL 1: Edit Options --}}
    <div class="modal fade" id="optionsModal" tabindex="-1" aria-labelledby="optionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="optionsModalLabel">Edit Opsi Pertanyaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3 text-muted" id="modal-quest-label"></p>
                    <div id="options-container" class="mb-3">
                        </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addOptionBtn">➕ Tambah Opsi Baru</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="saveOptionsBtn">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL 2: Atur Kondisi --}}
    <div class="modal fade" id="conditionalModal" tabindex="-1" aria-labelledby="conditionalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="conditionalModalLabel">🎯 Atur Kondisi Nonaktif Quest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3 text-muted" id="conditional-quest-label"></p>
                    <div id="conditional-options-body">
                        </div>
                    <div class="alert alert-warning mt-3">
                        Pilih Quest mana yang akan dinonaktifkan/disembunyikan jika opsi jawaban tersebut terpilih.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="saveConditionalBtn">Simpan Aturan</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Setup Variabel Global ---
        const optionsModal = document.getElementById('optionsModal');
        const optionsContainer = document.getElementById('options-container');
        const addOptionBtn = document.getElementById('addOptionBtn');
        const saveOptionsBtn = document.getElementById('saveOptionsBtn');
        let currentOptionsInput = null; // Ref input Options (untuk Modal Opsi)

        const conditionalModal = document.getElementById('conditionalModal');
        const conditionalOptionsBody = document.getElementById('conditional-options-body');
        const saveConditionalBtn = document.getElementById('saveConditionalBtn');
        let currentConditionalInput = null; // Ref input Conditional (untuk Modal Kondisi)
        let allQuests = {}; // Untuk menyimpan daftar semua Quest sebagai target

        // --- UTILITY FOR OPTIONS MODAL (Sama seperti sebelumnya) ---

        /** Membuat elemen input untuk opsi baru */
        function createOptionInput(value = '') {
            const wrapper = document.createElement('div');
            wrapper.className = 'input-group mb-2';
            
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control option-value';
            input.placeholder = 'Masukkan nilai opsi';
            input.value = value.trim();

            const buttonWrapper = document.createElement('button');
            buttonWrapper.className = 'btn btn-outline-danger btn-remove-option';
            buttonWrapper.type = 'button';
            buttonWrapper.innerHTML = 'Hapus';
            
            buttonWrapper.addEventListener('click', function() {
                wrapper.remove();
            });

            wrapper.appendChild(input);
            wrapper.appendChild(buttonWrapper);
            return wrapper;
        }

        addOptionBtn.addEventListener('click', function() {
            optionsContainer.appendChild(createOptionInput());
        });

        // 1. Logic Modal Opsi - Mengambil data
        optionsModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; 
            const optionsString = button.getAttribute('data-current-options');
            const questLabel = button.getAttribute('data-quest-label');
            
            currentOptionsInput = button.closest('td').querySelector('.options-input');
            
            document.getElementById('modal-quest-label').textContent = `Pertanyaan: ${questLabel}`;
            
            optionsContainer.innerHTML = ''; 

            if (optionsString) {
                const optionsArray = optionsString.split(',').map(item => item.trim()).filter(item => item !== "");
                optionsArray.forEach(option => {
                    optionsContainer.appendChild(createOptionInput(option));
                });
            }

            if (optionsContainer.children.length === 0) {
                 optionsContainer.appendChild(createOptionInput());
            }
        });

        // 2. Logic Modal Opsi - Menyimpan data
        saveOptionsBtn.addEventListener('click', function() {
            const optionInputs = optionsContainer.querySelectorAll('.option-value');
            let newOptionsArray = [];
            
            optionInputs.forEach(input => {
                const value = input.value.trim();
                if (value !== "") {
                    newOptionsArray.push(value);
                }
            });

            const newOptionsString = newOptionsArray.join(', ');
            
            if (currentOptionsInput) {
                currentOptionsInput.value = newOptionsString;
                
                // Update data-current-options pada tombol untuk sesi berikutnya
                currentOptionsInput.closest('td').querySelector('.btn-options').setAttribute('data-current-options', newOptionsString);
            }
            
            const modalInstance = bootstrap.Modal.getInstance(optionsModal);
            if (modalInstance) {
                modalInstance.hide();
            }
        });

        // --- CONDITIONAL LOGIC MODAL (FOKUS BARU) ---

        // Helper untuk membuat grup checkbox target
        function createTargetCheckboxes(optionsKey, currentLogic) {
            let html = '';
            
            // Konversi targets JSON dari PHP (yang berisi key:id) menjadi format yang mudah dibaca
            const targets = JSON.parse(document.querySelector(`.btn-conditional[data-quest-key="${optionsKey}"]`).getAttribute('data-targets'));
            
            // Simpan targets ke variabel global agar mudah diakses di logic simpan
            allQuests = targets; 

            // Grouping berdasarkan opsi jawaban
            const options = document.querySelector(`.btn-conditional[data-quest-key="${optionsKey}"]`).getAttribute('data-options').split(',').map(item => item.trim()).filter(item => item !== "");
            
            options.forEach(option => {
                // Periksa quest mana yang sudah terpilih untuk opsi ini
                const selectedTargets = currentLogic[option] || []; 
                
                html += `<div class="card card-body mb-3 bg-light">
                            <h6>Opsi Jawaban: <strong>"${option}"</strong></h6>
                            <small class="text-muted mb-2">Pilih quest lain yang harus dinonaktifkan/disembunyikan saat jawaban ini dipilih:</small>
                            <div class="row">`;

                // Iterasi semua Quest lain sebagai TARGET
                for (const questId in targets) {
                    const targetKey = targets[questId];
                    // Skip quest saat ini
                    if (targetKey === optionsKey) continue; 
                    
                    const isChecked = selectedTargets.includes(targetKey) ? 'checked' : '';
                    
                    html += `<div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input conditional-target-checkbox" type="checkbox" 
                                        data-option="${option}"
                                        value="${targetKey}" 
                                        id="target_${optionsKey}_${targetKey}" ${isChecked}>
                                    <label class="form-check-label" for="target_${optionsKey}_${targetKey}">
                                        ${targetKey}
                                    </label>
                                </div>
                             </div>`;
                }
                
                html += `</div></div>`;
            });

            conditionalOptionsBody.innerHTML = html;
        }

        // 1. Logic Modal Kondisional - Mengambil data
        conditionalModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; 
            const questLabel = button.getAttribute('data-quest-label');
            const questKey = button.getAttribute('data-quest-key');
            const currentLogicString = button.getAttribute('data-current-logic');
            
            currentConditionalInput = button.closest('td').querySelector('.conditional-input');
            
            document.getElementById('conditional-quest-label').textContent = `Quest Pemicu: ${questKey} - ${questLabel}`;
            
            try {
                const currentLogic = JSON.parse(currentLogicString);
                createTargetCheckboxes(questKey, currentLogic);
            } catch (e) {
                console.error("Error parsing current conditional logic:", e);
                createTargetCheckboxes(questKey, {});
            }
        });

        // 2. Logic Modal Kondisional - Menyimpan data
        saveConditionalBtn.addEventListener('click', function() {
            let newLogic = {};
            const checkboxes = conditionalOptionsBody.querySelectorAll('.conditional-target-checkbox:checked');
            
            checkboxes.forEach(checkbox => {
                const option = checkbox.getAttribute('data-option');
                const targetKey = checkbox.value;
                
                if (!newLogic[option]) {
                    newLogic[option] = [];
                }
                newLogic[option].push(targetKey);
            });
            
            const newLogicString = JSON.stringify(newLogic);

            if (currentConditionalInput) {
                // Simpan JSON string ke input hidden
                currentConditionalInput.value = newLogicString;
                
                // Update data-current-logic pada tombol untuk sesi berikutnya
                currentConditionalInput.closest('td').querySelector('.btn-conditional').setAttribute('data-current-logic', newLogicString);
            }
            
            const modalInstance = bootstrap.Modal.getInstance(conditionalModal);
            if (modalInstance) {
                modalInstance.hide();
            }
        });


        // 3. Logic Tampilan - Menyembunyikan/Menampilkan tombol Options (Sama seperti sebelumnya)
        function toggleOptionsVisibility(selectElement) {
            const row = selectElement.closest('tr');
            const optionsTd = row.querySelector('.options-input').closest('td');
            const conditionalTd = optionsTd.nextElementSibling;

            const optionsButton = optionsTd.querySelector('.btn-options');
            const conditionalButton = conditionalTd.querySelector('.btn-conditional');
            
            const selectedType = selectElement.value;
            
            if (selectedType === 'radio' || selectedType === 'dropdown') {
                optionsButton.style.display = 'inline-block';
                conditionalButton.style.display = 'inline-block';
            } else {
                optionsButton.style.display = 'none';
                conditionalButton.style.display = 'none';
                // Kosongkan input options dan conditional jika bukan tipe opsi
                row.querySelector('.options-input').value = ''; 
                row.querySelector('.conditional-input').value = '';
            }
        }

        // Jalankan saat load (initial state)
        document.querySelectorAll('.quest-type-select').forEach(select => {
            toggleOptionsVisibility(select);
            
            select.addEventListener('change', function() {
                toggleOptionsVisibility(this);
            });
        });
    });
</script>
@endsection