@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">🧮 Konfigurasi Rumus Status Ketenagakerjaan</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Rumus</h5>
                        <small class="text-muted">Kelola logika penentuan status Bekerja/Pengangguran</small>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Rumus</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rumus as $r)
                                    <tr class="{{ $r->is_active ? 'table-success' : '' }}">
                                        <td><strong>{{ $r->nama_rumus }}</strong></td>
                                        <td>{{ $r->deskripsi }}</td>
                                        <td>
                                            @if ($r->is_active)
                                                <span class="badge bg-success">✅ Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">⏸️ Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.config.rumus.edit', $r->id) }}"
                                                class="btn btn-sm btn-primary">
                                                ✏️ Edit Rumus
                                            </a>
                                            @if (!$r->is_active)
                                                <form action="{{ route('admin.config.rumus.toggle', $r->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Aktifkan rumus ini?')">
                                                        ✅ Aktifkan
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('admin.config.quest') }}" class="btn btn-info">📝 Kelola Quest</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">← Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endsection
