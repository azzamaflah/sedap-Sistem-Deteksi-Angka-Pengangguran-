@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">⚙️ Konfigurasi Quest Responden</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
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
                                    <th width="15%">Type</th>
                                    <th width="15%">Options</th>
                                    <th width="10%">Status</th>
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
                                                <select name="type" class="form-select form-select-sm" required>
                                                    <option value="radio" {{ $quest->type == 'radio' ? 'selected' : '' }}>
                                                        Radio</option>
                                                    <option value="text" {{ $quest->type == 'text' ? 'selected' : '' }}>
                                                        Text</option>
                                                    <option value="textarea"
                                                        {{ $quest->type == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="options" class="form-control form-control-sm"
                                                    value="{{ is_array($quest->options) ? implode(', ', $quest->options) : '' }}"
                                                    placeholder="Ya, Tidak">
                                                <small class="text-muted">Pisahkan dengan koma</small>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_active"
                                                        {{ $quest->is_active ? 'checked' : '' }}>
                                                    <label
                                                        class="form-check-label">{{ $quest->is_active ? 'Aktif' : 'Nonaktif' }}</label>
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
@endsection
