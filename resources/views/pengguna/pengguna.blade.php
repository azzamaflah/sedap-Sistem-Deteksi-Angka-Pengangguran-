@extends('layouts.app')

@section('title', 'Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengguna</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <form method="GET" class="d-flex gap-2 flex-grow-1">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, username, atau email..."
                    value="{{ request('search') }}" style="max-width: 400px;">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                @if (request('search'))
                    <a href="{{ route('pengguna.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i>
                        Reset</a>
                @endif
            </form>
            <a href="{{ route('pengguna.create') }}" class="btn btn-success ms-2"><i class="bi bi-person-plus-fill"></i>
                Tambah Pengguna</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>ID User</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td>{{ $row->no }}</td>
                                <td><span class="badge bg-info">{{ $row->id_user }}</span></td>
                                <td><strong>{{ $row->nama }}</strong></td>
                                <td>{{ $row->user_name }}</td>
                                <td>{{ $row->email ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('pengguna.edit', $row->no) }}" class="btn btn-warning btn-sm"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('pengguna.destroy', $row->no) }}" method="POST"
                                        style="display:inline;" onsubmit="return confirmDelete();">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Hapus"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                                    <p class="text-muted">Tidak ada data pengguna.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }}
                    data
                </div>
                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
