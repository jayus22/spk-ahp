@extends('layouts.admin')

@section('title', 'Kelola Laptop')

@section('styles')
    <style>
        .btn-action {
            margin-right: 5px;
        }

        .search-form {
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fas fa-laptop"></i> Daftar Laptop</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('/admin/laptops/create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Laptop Baru
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('/admin/laptops') }}" method="GET" class="search-form">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari laptop..."
                                        value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="brand" class="form-select">
                                    <option value="">Semua Merek</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand }}"
                                            {{ request('brand') == $brand ? 'selected' : '' }}>
                                            {{ $brand }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="price_range" class="form-select">
                                    <option value="">Semua Harga</option>
                                    <option value="low" {{ request('price_range') == 'low' ? 'selected' : '' }}>
                                        < Rp 10.000.000 </option>
                                    <option value="medium" {{ request('price_range') == 'medium' ? 'selected' : '' }}>
                                        Rp 10.000.000 - Rp 20.000.000
                                    </option>
                                    <option value="high" {{ request('price_range') == 'high' ? 'selected' : '' }}>
                                        > Rp 20.000.000
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ url('/admin/laptops') }}" class="btn btn-secondary w-100">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table-striped table-hover table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Gambar</th>
                                    <th>Nama</th>
                                    <th>Merek</th>
                                    <th>Harga (Rp)</th>
                                    <th>Prosesor</th>
                                    <th>RAM</th>
                                    <th>Penyimpanan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laptops as $laptop)
                                    <tr>
                                        <td>{{ $laptop->id }}</td>
                                        <td>
                                            @if ($laptop->image)
                                                <img src="{{ asset('storage/' . $laptop->image) }}"
                                                    alt="{{ $laptop->name }}" width="50">
                                            @else
                                                <img src="{{ asset('images/no-image.png') }}" alt="No Image"
                                                    width="50">
                                            @endif
                                        </td>
                                        <td>{{ $laptop->name }}</td>
                                        <td>{{ $laptop->brand }}</td>
                                        <td>{{ number_format($laptop->price, 0, ',', '.') }}</td>
                                        <td>{{ $laptop->processor }}</td>
                                        <td>{{ $laptop->ram }} GB</td>
                                        <td>{{ $laptop->storage }} GB {{ $laptop->storage_type }}</td>
                                        <td>
                                            @if ($laptop->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('/admin/laptops/' . $laptop->id . '/edit') }}"
                                                class="btn btn-sm btn-primary btn-action">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ url('/admin/laptops/' . $laptop->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger btn-action"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus laptop ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <form action="{{ url('/admin/laptops/' . $laptop->id . '/toggle-status') }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm {{ $laptop->is_active ? 'btn-warning' : 'btn-success' }} btn-action">
                                                    <i class="fas {{ $laptop->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data laptop ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $laptops->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Konfirmasi penghapusan
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Apakah Anda yakin ingin menghapus laptop ini?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
