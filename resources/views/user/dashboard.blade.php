@extends('layouts.user')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <!-- Welcome Message -->
        <div class="card mb-4 shadow">
            <div class="card-header d-flex align-items-center justify-content-between flex-row py-3">
                <h6 class="font-weight-bold text-primary m-0">Selamat Datang</h6>
            </div>
            <div class="card-body">
                <p>Selamat datang di Sistem Pendukung Keputusan Pemilihan Laptop.</p>
                <p>Sistem ini akan membantu Anda menemukan laptop yang sesuai dengan kebutuhan dan preferensi Anda
                    menggunakan metode AHP (Analytical Hierarchy Process) dan TOPSIS (Technique for Order of Preference by
                    Similarity to Ideal Solution).</p>
                <hr>
                <div class="text-center">
                    <a href="{{ route('user.decision-form') }}" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-laptop mr-2"></i> Mulai Pemilihan Laptop
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Statistics Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-primary text-uppercase mb-1 text-xs">
                                    Total Laptop Tersedia</div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">{{ $laptopCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-laptop fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-success text-uppercase mb-1 text-xs">
                                    Jumlah Kriteria</div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">{{ $criteriaCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-list-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-info text-uppercase mb-1 text-xs">
                                    Jumlah Pencarian Anda</div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">{{ $userSearchCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-search fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Results -->
        <div class="card mb-4 shadow">
            <div class="card-header d-flex align-items-center justify-content-between flex-row py-3">
                <h6 class="font-weight-bold text-primary m-0">Hasil Pencarian Terakhir</h6>
                <a href="{{ route('user.results.history') }}" class="btn btn-sm btn-info">
                    Lihat Semua Hasil
                </a>
            </div>
            <div class="card-body">
                @if ($recentResults->count() > 0)
                    <div class="table-responsive">
                        <table class="table-bordered table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Laptop Terpilih</th>
                                    <th>Skor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentResults as $result)
                                    <tr>
                                        <td>{{ $result->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $result->topLaptop->brand }} {{ $result->topLaptop->model }}</td>
                                        <td>{{ number_format($result->top_score, 4) }}</td>
                                        <td>
                                            <a href="{{ route('user.results.show', $result->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-4 text-center">
                        <i class="fas fa-search fa-3x mb-3 text-gray-300"></i>
                        <p>Anda belum melakukan pencarian laptop.</p>
                        <a href="{{ route('user.decision-form') }}" class="btn btn-primary">
                            Mulai Pencarian
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Popular Laptops -->
        <div class="card mb-4 shadow">
            <div class="card-header py-3">
                <h6 class="font-weight-bold text-primary m-0">Laptop Populer</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($popularLaptops as $laptop)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $laptop->brand }} {{ $laptop->model }}</h5>
                                    <h6 class="card-subtitle text-muted mb-2">Rp
                                        {{ number_format($laptop->price, 0, ',', '.') }}</h6>
                                    <p class="card-text">
                                        <strong>Processor:</strong> {{ $laptop->processor }}<br>
                                        <strong>RAM:</strong> {{ $laptop->ram }} GB<br>
                                        <strong>Storage:</strong> {{ $laptop->storage }}<br>
                                        <strong>Display:</strong> {{ $laptop->display }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="#" class="btn btn-sm btn-outline-primary">Detail Laptop</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
