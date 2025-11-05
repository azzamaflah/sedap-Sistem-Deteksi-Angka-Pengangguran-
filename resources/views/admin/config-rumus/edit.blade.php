@extends('layouts.app')

@section('content')
    <style>
        .json-editor {
            font-family: 'Courier New', monospace;
            background: #2d2d2d;
            color: #f8f8f2;
            border-radius: 8px;
            padding: 15px;
        }

        .help-section {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-top: 15px;
            border-radius: 4px;
        }
    </style>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>✏️ Edit Rumus Status</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.config.rumus.update', $rumus->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Info Umum -->
                            <div class="mb-4">
                                <h5>ℹ️ Informasi Rumus</h5>

                                <div class="mb-3">
                                    <label class="form-label">Nama Rumus <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_rumus"
                                        class="form-control @error('nama_rumus') is-invalid @enderror"
                                        value="{{ old('nama_rumus', $rumus->nama_rumus) }}" required>
                                    @error('nama_rumus')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $rumus->deskripsi) }}</textarea>
                                </div>

                                <div class="form-check form-switch">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            {{ old('is_active', $rumus->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            <strong>Aktifkan Rumus Ini</strong> (Rumus lain akan otomatis dinonaktifkan)
                                        </label>
                                    </div>
                                </div>

                                <hr>

                                <!-- Kondisi Bekerja -->
                                <div class="mb-4">
                                    <h5>✅ Kondisi Status "Bekerja"</h5>
                                    <p class="text-muted">Definisikan kondisi yang harus dipenuhi agar responden berstatus
                                        <strong>Bekerja</strong></p>

                                    <label class="form-label">JSON Kondisi Bekerja <span
                                            class="text-danger">*</span></label>
                                    <textarea name="kondisi_bekerja" class="form-control json-editor @error('kondisi_bekerja') is-invalid @enderror"
                                        rows="10" required>{{ old('kondisi_bekerja', json_encode($rumus->kondisi_bekerja, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                                    @error('kondisi_bekerja')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div class="help-section">
                                        <strong>📖 Contoh Format JSON:</strong>
                                        <pre class="mb-0 mt-2" style="background: white; padding: 10px; border-radius: 4px;">
{
  "operator": "OR",
  "conditions": [
    {"field": "r7_1", "value": "Ya"},
    {"field": "r7_2", "value": "Ya"},
    {"field": "r7_3", "value": "Ya"},
    {"field": "r8_1", "value": "Ya"}
  ]
}</pre>
                                        <small class="text-muted mt-2 d-block">
                                            <strong>Operator:</strong> "OR" = salah satu kondisi terpenuhi | "AND" = semua
                                            kondisi harus terpenuhi
                                        </small>
                                    </div>
                                </div>

                                <hr>

                                <!-- Kondisi Pengangguran -->
                                <div class="mb-4">
                                    <h5>❌ Kondisi Status "Pengangguran"</h5>
                                    <p class="text-muted">Definisikan kondisi yang harus dipenuhi agar responden berstatus
                                        <strong>Pengangguran</strong></p>

                                    <label class="form-label">JSON Kondisi Pengangguran <span
                                            class="text-danger">*</span></label>
                                    <textarea name="kondisi_pengangguran"
                                        class="form-control json-editor @error('kondisi_pengangguran') is-invalid @enderror" rows="10" required>{{ old('kondisi_pengangguran', json_encode($rumus->kondisi_pengangguran, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                                    @error('kondisi_pengangguran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div class="help-section">
                                        <strong>📖 Contoh Format JSON:</strong>
                                        <pre class="mb-0 mt-2" style="background: white; padding: 10px; border-radius: 4px;">
{
  "operator": "AND",
  "conditions": [
    {"field": "r7_1", "value": "Tidak"},
    {"field": "r7_2", "value": "Tidak"},
    {"field": "r7_3", "value": "Tidak"},
    {"field": "r8_1", "value": "Tidak"}
  ]
}</pre>
                                    </div>
                                </div>

                                <hr>

                                <!-- Available Fields Reference -->
                                <div class="alert alert-info">
                                    <h6>📝 Field Quest yang Tersedia:</h6>
                                    <div class="row">
                                        @foreach ($quests as $quest)
                                            <div class="col-md-6 mb-2">
                                                <code>{{ $quest->key }}</code> - {{ $quest->label }}
                                                @if ($quest->type === 'radio' && $quest->options)
                                                    <br><small class="text-muted">Opsi:
                                                        {{ implode(', ', $quest->getOptionsArray()) }}</small>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.config.rumus') }}" class="btn btn-secondary">
                                        ← Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        💾 Simpan Perubahan
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
            // JSON Validation on Submit
            const form = document.querySelector('form');
            const kondisiBekerja = document.querySelector('textarea[name="kondisi_bekerja"]');
            const kondisiPengangguran = document.querySelector('textarea[name="kondisi_pengangguran"]');

            form.addEventListener('submit', function(e) {
                let valid = true;

                // Validate JSON Bekerja
                try {
                    JSON.parse(kondisiBekerja.value);
                    kondisiBekerja.classList.remove('is-invalid');
                } catch (error) {
                    e.preventDefault();
                    kondisiBekerja.classList.add('is-invalid');
                    alert('❌ Format JSON Kondisi Bekerja tidak valid!\n\n' + error.message);
                    valid = false;
                }

                // Validate JSON Pengangguran
                try {
                    JSON.parse(kondisiPengangguran.value);
                    kondisiPengangguran.classList.remove('is-invalid');
                } catch (error) {
                    if (valid) { // Only prevent if not already prevented
                        e.preventDefault();
                    }
                    kondisiPengangguran.classList.add('is-invalid');
                    alert('❌ Format JSON Kondisi Pengangguran tidak valid!\n\n' + error.message);
                    valid = false;
                }

                if (valid) {
                    return confirm('💾 Simpan perubahan rumus ini?');
                }
            });

            // Pretty print JSON on load
            function formatJSON(textarea) {
                try {
                    const obj = JSON.parse(textarea.value);
                    textarea.value = JSON.stringify(obj, null, 2);
                } catch (e) {
                    // Keep original if invalid
                }
            }

            formatJSON(kondisiBekerja);
            formatJSON(kondisiPengangguran);
        });
    </script>
@endsection
