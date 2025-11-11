@extends('layouts.app')

{{-- Perubahan 1: Tambahkan Title, Page Title, dan Breadcrumb --}}
@section('title', 'Kelola Quest')
@section('page-title', 'Konfigurasi Quest Responden')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kelola Quest</li>
@endsection

{{-- Perubahan 2: Salin Styles dari Pengguna --}}
@section('styles')
    <style>
        /* ===== STYLES DARI RESPONDEN.BLADE.PHP UNTUK KONSISTENSI VISUAL ===== */
        /* Card Header Enhancement */
        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 2px solid #f0f0f0 !important;
        }

        /* Table Row Hover/Click Effect */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            /* Light primary color hover */
            cursor: default; /* Ubah ke default karena tidak ada aksi klik baris */
            transition: background-color 0.3s ease;
        }

        /* ===== ACTION BUTTON GROUP STYLES (Diperlukan untuk tombol Update/Aksi Modal) ===== */
        .btn-action-group {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .btn-custom {
            position: relative;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-custom:active {
            transform: translateY(-1px);
        }

        /* Style untuk tombol Update di tabel */
        .btn-sm.btn-primary,
        .btn-sm.btn-info,
        .btn-sm.btn-warning {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .btn-sm.btn-primary:hover,
        .btn-sm.btn-info:hover,
        .btn-sm.btn-warning:hover {
             transform: translateY(-1px);
             box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Icon/Element Style di Tabel */
        .table .form-control-sm,
        .table .form-select-sm {
            height: 30px;
            padding: 0.25rem 0.5rem;
        }
        
        /* Switch/Toggle Style (Tambahan untuk tampilan) */
        .form-switch .form-check-input {
            width: 38px;
            height: 20px;
        }
        
        .form-check-label {
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .table-responsive {
                border: 1px solid #dee2e6; /* Tambahkan border di mobile/tablet */
                border-radius: 0.375rem;
            }
        }
        
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {!! nl2br(e(session('success'))) !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> Terjadi kesalahan:
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Perubahan 5: Tambahkan kelas shadow-sm dan fade-in --}}
                <div class="card shadow-sm fade-in">
                    {{-- Perubahan 6: Sesuaikan Card Header --}}
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Daftar Pertanyaan (Quest)</h5>
                        <small class="text-muted">Ubah urutan, label, dan konfigurasi pertanyaan yang ditampilkan di form responden</small>
                    </div>
                    
                    {{-- Perubahan 7: Tambahkan p-0 untuk table-responsive agar full-width --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            {{-- Perubahan 8: Tambahkan kelas table-hover --}}
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">Order</th>
                                        <th style="width: 100px;">Key</th>
                                        <th style="width: 35%;">Label Pertanyaan</th>
                                        <th style="width: 100px;">Type</th>
                                        <th class="text-center" style="width: 120px;">Options</th>
                                        <th class="text-center" style="width: 120px;">Kondisi</th>
                                        <th class="text-center" style="width: 80px;">Status</th>
                                        <th class="text-center" style="width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quests as $index => $quest)
                                        {{-- Tambahkan animasi per baris --}}
                                        <tr class="fade-in" style="animation-delay: {{ $index * 0.05 }}s;">
                                            <form action="{{ route('admin.config.quest.update', $quest->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <td>
                                                    <input type="number" name="order" class="form-control form-control-sm text-center"
                                                        value="{{ $quest->order }}" required style="max-width: 60px; margin: 0 auto;">
                                                </td>
                                                <td>
                                                    <span class="">{{ $quest->key }}</span>
                                                </td>
                                                <td>
                                                    <input type="text" name="label" class="form-control form-control-sm"
                                                        value="{{ $quest->label }}" required>
                                                    <small>
                                                        <input type="text" name="description"
                                                            class="form-control form-control-sm mt-1"
                                                            placeholder="Deskripsi (opsional)"
                                                            value="{{ $quest->description }}">
                                                    </small>
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
                                                <td class="text-center">
                                                    <input type="hidden" name="options" class="form-control form-control-sm options-input"
                                                        value="{{ is_array($quest->options) ? implode(', ', $quest->options) : '' }}">
                                                    
                                                    <button type="button" class="btn btn-sm btn-info btn-options"
                                                        data-bs-toggle="modal" data-bs-target="#optionsModal"
                                                        data-current-options="{{ is_array($quest->options) ? implode(', ', $quest->options) : '' }}"
                                                        data-quest-label="{{ $quest->label }}"
                                                        data-bs-placement="top" title="Kelola Pilihan Jawaban">
                                                        ⚙️ Edit Opsi
                                                    </button>
                                                </td>

                                                {{-- Kolom Atur Kondisi (Modal Kondisi) --}}
                                                <td class="text-center">
                                                    <input type="hidden" name="conditional_target" class="conditional-input"
                                                        value="{{ $quest->conditional_target ? json_encode($quest->conditional_target) : '' }}">
                                                    
                                                    <button type="button" class="btn btn-sm btn-warning btn-conditional"
                                                        data-bs-toggle="modal" data-bs-target="#conditionalModal"
                                                        data-quest-id="{{ $quest->id }}"
                                                        data-quest-key="{{ $quest->key }}"
                                                        data-quest-label="{{ $quest->label }}"
                                                        data-options="{{ is_array($quest->options) ? implode(',', $quest->options) : '' }}"
                                                        data-targets='@json($quests->pluck('key', 'id')->toArray())'
                                                        data-current-logic="{{ $quest->conditional_target ? json_encode($quest->conditional_target) : '{}' }}"
                                                        data-bs-placement="top" title="Atur Logika Sembunyi">
                                                        🎯 Atur Kondisi
                                                    </button>
                                                </td>

                                                <td class="text-center">
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input type="hidden" name="is_active" value="0">
                                                        
                                                        <input class="form-check-input" type="checkbox" name="is_active"
                                                            value="1"
                                                            id="is_active_{{ $quest->id }}"
                                                            {{ $quest->is_active ? 'checked' : '' }}
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $quest->is_active ? 'Status: Aktif' : 'Status: Nonaktif' }}">
                                                        
                                                        {{-- Hapus Label untuk menghemat ruang, gunakan Tooltip sebagai gantinya --}}
                                                        {{-- <label class="form-check-label" for="is_active_{{ $quest->id }}">
                                                            {{ $quest->is_active ? 'Aktif' : 'Nonaktif' }}
                                                        </label> --}}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button type="submit" class="btn btn-custom btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Simpan Perubahan">
                                                        💾 Update
                                                    </button>
                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    {{-- Perubahan 9: Tambahkan Card Footer untuk tombol navigasi --}}
                    <div class="card-footer bg-white fade-in" style="animation-delay: 0.2s;">
                         <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">Total {{ $quests->count() }} Pertanyaan</div>
                            <div class="btn-action-group">
                                <a href="{{ route('admin.config.rumus') }}" class="btn btn-custom btn-warning btn-sm" data-bs-toggle="tooltip" title="Kelola Rumus Status">
                                    <i class="bi bi-calculator"></i> Kelola Rumus Status
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn btn-custom btn-secondary btn-sm" data-bs-toggle="tooltip" title="Kembali ke Halaman Utama">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL 1: Edit Options (Tidak Ada Perubahan Visual Signifikan) --}}
    <div class="modal fade" id="optionsModal" tabindex="-1" aria-labelledby="optionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="optionsModalLabel">⚙️ Edit Opsi Pertanyaan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

    {{-- MODAL 2: Atur Kondisi (Tidak Ada Perubahan Visual Signifikan) --}}
    <div class="modal fade" id="conditionalModal" tabindex="-1" aria-labelledby="conditionalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
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

{{-- Perubahan 10: Salin Scripts dari Pengguna --}}
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
        
        // ===================================
        // NEW: Smooth scroll reveal animation (diperlukan untuk .fade-in)
        // ===================================
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target); // Stop observing once visible
                }
            });
        }, {
            threshold: 0.1
        });
         
        // ===================================
        // NEW: Bootstrap Tooltip
        // ===================================
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        // ===================================


        // --- UTILITY FOR OPTIONS MODAL (Sama seperti sebelumnya) ---

        /** Membuat elemen input untuk opsi baru */
        function createOptionInput(value = '') {
            const wrapper = document.createElement('div');
            wrapper.className = 'input-group mb-2';
            
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control form-control-sm option-value'; // Tambah form-control-sm
            input.placeholder = 'Masukkan nilai opsi';
            input.value = value.trim();

            const buttonWrapper = document.createElement('button');
            buttonWrapper.className = 'btn btn-outline-danger btn-sm btn-remove-option'; // Tambah btn-sm
            buttonWrapper.type = 'button';
            buttonWrapper.innerHTML = '<i class="bi bi-trash"></i> Hapus'; // Ganti teks dengan ikon
            
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
            
            if (options.length === 0) {
                 conditionalOptionsBody.innerHTML = '<div class="alert alert-info">Quest ini belum memiliki opsi jawaban. Silakan atur opsi jawaban terlebih dahulu.</div>';
                 saveConditionalBtn.disabled = true;
                 return;
            }
            saveConditionalBtn.disabled = false;


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
                                     <label class="form-check-label" for="target_${optionsKey}_${targetKey}" data-bs-toggle="tooltip" title="${targetKey}">
                                         ${targetKey.length > 15 ? targetKey.substring(0, 15) + '...' : targetKey} 
                                     </label>
                                 </div>
                              </div>`;
                }
                
                html += `</div></div>`;
            });

            conditionalOptionsBody.innerHTML = html;
            // Re-inisialisasi Tooltip setelah konten modal diupdate
            var tooltipTargetList = [].slice.call(document.querySelectorAll('#conditionalModal [data-bs-toggle="tooltip"]'));
            tooltipTargetList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

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
            const conditionalTd = optionsTd.nextElementSibling; // Asumsi Kondisi adalah kolom setelah Options

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