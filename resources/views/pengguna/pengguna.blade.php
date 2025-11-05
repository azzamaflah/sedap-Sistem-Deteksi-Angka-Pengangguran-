@extends('layouts.app')

@section('title', 'Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengguna</li>
@endsection

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
            background-color: rgba(0, 123, 255, 0.05); /* Light primary color hover */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* ===== ACTION BUTTON GROUP STYLES ===== */
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

        /* PERBAIKAN: STYLE UNTUK TOMBOL TAMBAH PENGGUNA (PRIMARY/BIRU) */
        .btn-add { /* Menggunakan .btn-add */
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); 
            color: white;
            z-index: 0;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            color: white;
        }

        /* Hapus definisi .btn-add-user yang lama jika ada konflik */
        /* .btn-add-user { ... } */


        /* Icon Animation */
        .btn-custom i {
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .btn-custom:hover i {
            transform: scale(1.2) rotate(5deg);
        }

        .btn-add:hover i { /* Perbaikan: Memastikan animasi rotate360 terpanggil ke .btn-add */
            animation: rotate360 0.6s ease;
        }

        @keyframes rotate360 {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* ===== SEARCH BAR STYLES ===== */
        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%; /* Agar mengambil lebar penuh kolom */
        }

        .search-wrapper .form-control {
            padding-left: 50px; /* Diubah dari 25px agar tidak terlalu menempel ke ikon */
            padding-right: 90px;
            border-radius: 50px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            height: 45px;
            width: 100%;
        }

        .search-wrapper .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
            outline: none;
        }

        .search-wrapper .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 18px;
            z-index: 10;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .search-wrapper .form-control:focus~.search-icon {
            color: #007bff;
            animation: searchPulse 0.5s ease;
        }

        @keyframes searchPulse {

            0%,
            100% {
                transform: translateY(-50%) scale(1);
            }

            50% {
                transform: translateY(-50%) scale(1.2);
            }
        }

        /* Clear Button (Reset) */
        .btn-clear-search {
            position: absolute;
            right: 50px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #dc3545;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-clear-search:hover {
            color: #bd2130;
            transform: translateY(-50%) scale(1.2) rotate(90deg);
        }

        /* Search Submit Button */
        .search-wrapper .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }

        .search-wrapper .search-btn:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-50%) scale(1.15);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        }

        .search-wrapper .search-btn i {
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .search-wrapper .search-btn:hover i {
            transform: scale(1.2);
        }

        /* Modifikasi Tombol Aksi */
        .btn-action-group .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .btn-action-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-custom {
                width: 100%;
            }

            .search-wrapper .form-control {
                padding-right: 50px;
            }

            .btn-clear-search {
                right: 50px;
            }
            
            .search-wrapper .search-btn {
                right: 5px; /* Sesuaikan posisi tombol search */
            }
        }
    </style>
@endsection

@section('content')
    {{-- Tambahkan Alerts untuk konsistensi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
            <i class="bi bi-check-circle-fill"></i> {!! nl2br(e(session('success'))) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {!! nl2br(e(session('error'))) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm fade-in">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center g-3">
                
                {{-- Kiri: Search Bar (Diubah ke format Responden) --}}
                <div class="col-lg-7 col-md-12">
                    <form method="GET" class="search-wrapper">
                        
                        {{-- Ikon Search --}}
                        <i class="bi bi-search search-icon"></i>
                        
                        <input type="text" name="search" id="searchInput" class="form-control"
                            placeholder="Cari nama, username, atau email..." value="{{ request('search') }}" style="max-width: 100%;">
                        
                        {{-- Clear Button/Reset Link --}}
                        @if (request('search'))
                            <a href="{{ route('pengguna.index') }}" class="btn-clear-search" data-bs-toggle="tooltip"
                                title="Hapus Pencarian">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif

                        {{-- Search Submit Button --}}
                        <button type="submit" class="search-btn" data-bs-toggle="tooltip" title="Cari">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <div class="col-lg-5 col-md-12">
                    <div class="btn-action-group justify-content-lg-end"> 
                        {{-- PERBAIKAN: Mengganti btn-add-user menjadi btn-add untuk warna primary --}}
                        <a href="{{ route('pengguna.create') }}" class="btn btn-custom btn-add" data-bs-toggle="tooltip"
                            title="Tambah Pengguna Baru">
                            <i class="bi bi-person-plus-fill"></i> Tambah Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center" style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $row)
                            <tr class="fade-in" style="animation-delay: {{ $index * 0.05 }}s;">
                                <td class="text-center">{{ $data->firstItem() + $index }}</td>
                                <td><strong>{{ $row->name }}</strong></td>
                                <td><span class="badge bg-info">{{ $row->username }}</span></td>
                                <td>{{ $row->email ?? '-' }}</td>
                                <td>
                                    @if ($row->role === 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">User</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-action-group" role="group">
                                        {{-- Edit Button (Menggunakan btn-outline-primary) --}}
                                        <a href="{{ route('pengguna.edit', $row->id) }}" class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="tooltip" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        {{-- Delete Button (Menggunakan btn-outline-danger) --}}
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip"
                                            title="Hapus Data" onclick="confirmDelete({{ $row->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $row->id }}"
                                        action="{{ route('pengguna.destroy', $row->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 fade-in" style="animation-delay: 0.1s;">
                                    <i class="bi bi-people fs-1 text-muted d-block mb-2" style="font-size: 3rem !important; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Tidak ada data pengguna.</p>
                                    <a href="{{ route('pengguna.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-person-plus-fill"></i> Tambah Pengguna Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white fade-in" style="animation-delay: 0.2s;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }}
                    data
                </div>
                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll reveal animation (diperlukan untuk .fade-in)
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
            
            // PENTING: Panggil observer untuk semua elemen .fade-in
            document.querySelectorAll('.fade-in').forEach(element => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(10px)';
                element.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                observer.observe(element);
            });
            
            // Bootstrap Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Konfirmasi Delete
            window.confirmDelete = function(id) {
                if (confirm(
                        '⚠️ Yakin ingin menghapus pengguna ini?\n\nData pengguna yang sudah dihapus tidak dapat dikembalikan!'
                    )) {
                    document.getElementById('delete-form-' + id).submit();
                }
            };
            
            // Logika untuk tombol Clear Search (Reset)
            const searchInput = document.getElementById('searchInput');
            const btnClearSearch = document.querySelector('.btn-clear-search');

            if (searchInput && btnClearSearch) {
                // Sembunyikan/tampilkan tombol clear jika input kosong (untuk mobile)
                function toggleClearButton() {
                    if (window.innerWidth <= 768) {
                        btnClearSearch.style.display = searchInput.value ? 'flex' : 'none';
                    } else {
                         // Di desktop, tombol clear adalah link, jadi hanya tampil jika request('search') ada.
                    }
                }
                searchInput.addEventListener('input', toggleClearButton);
                toggleClearButton();
            }
        });
    </script>
@endsection