@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hasil Pemilihan Laptop</h1>
        <div>
            <a href="{{ route('user.decision-form') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-search fa-sm text-white-50"></i> Cari Kembali
            </a>
            <a href="{{ route('user.results.history') }}" class="btn btn-sm btn-info shadow-sm ml-2">
                <i class="fas fa-history fa-sm text-white-50"></i> Riwayat Pencarian
            </a>
        </div>
    </div>

    <!-- Alert -->
    <div class="alert alert-success mb-4">
        <i class="fas fa-info-circle mr-1"></i> Berikut adalah rekomendasi laptop berdasarkan preferensi Anda.
    </div>

    <!-- Top Recommendations -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Laptop Rekomendasi Terbaik</h6>
                    <span class="badge badge-success">Skor: {{ number_format($result->top_score, 4) }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="{{ asset('img/laptops/' . $result->topLaptop->image) }}" 
                                alt="{{ $result->topLaptop->brand }} {{ $result->topLaptop->model }}" 
                                class="img-fluid mb-3" style="max-height: 150px;">
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $result->topLaptop->brand }} {{ $result->topLaptop->model }}</h4>
                            <h5 class="text-primary">Rp {{ number_format($result->topLaptop->price, 0, ',', '.') }}</h5>
                            
                            <!-- Specifications -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p><strong>Processor:</strong> {{ $result->topLaptop->processor }}</p>
                                    <p><strong>RAM:</strong> {{ $result->topLaptop->ram }} GB</p>
                                    <p><strong>Storage:</strong> {{ $result->topLaptop->storage }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Display:</strong> {{ $result->topLaptop->display }}</p>
                                    <p><strong>GPU:</strong> {{ $result->topLaptop->gpu }}</p>
                                    <p><strong>Battery:</strong> {{ $result->topLaptop->battery }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt"></i> Lihat Detail
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-success ml-2">
                                    <i class="fas fa-shopping-cart"></i> Beli Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Parameter Pencarian</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Rentang Budget:</h6>
                        <p>Rp {{ number_format($result->min_budget, 0, ',', '.') }} - Rp {{ number_format($result->max_budget, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Bobot Kriteria:</h6>
                        <ul class="list-group list-group-flush">
                            @foreach($result->criteriaWeights as $weight)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    {{ $weight->criteria->name }}
                                    <span class="badge badge-primary badge-pill">{{ $weight->weight_value }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Preferensi Fitur:</h6>
                        <ul class="list-group list-group-flush">
                            @foreach($result->preferences as $preference)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    {{ $preference->criteria_name }}
                                    <span class="badge badge-info badge-pill">{{ $preference->value }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Tujuan Penggunaan:</h6>
                        <p>
                            @foreach($result->purposes as $purpose)
                                <span class="badge badge-secondary mr-1">{{ $purpose->name }}</span>
                            @endforeach
                        </p>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('user.print-result', $result->id) }}" class="btn btn-success btn-sm" target="_blank">
                            <i class="fas fa-print"></i> Cetak Hasil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alternative Recommendations -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Alternatif Rekomendasi Lainnya</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Peringkat</th>
                            <th>Laptop</th>
                            <th>Spesifikasi</th>
                            <th>Harga</th>
                            <th>Skor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result->rankings as $key => $ranking)
                            @if($key > 0 && $key < 6) <!-- Display rank 2-6 only -->
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <strong>{{ $ranking->laptop->brand }} {{ $ranking->laptop->model }}</strong>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $ranking->laptop->processor }}, 
                                            {{ $ranking->laptop->ram }}GB RAM, 
                                            {{ $ranking->laptop->storage }}, 
                                            {{ $ranking->laptop->display }}
                                        </small>
                                    </td>
                                    <td>Rp {{ number_format($ranking->laptop->price, 0, ',', '.') }}</td>
                                    <td>{{ number_format($ranking->score, 4) }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Analysis -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Analisis Perbandingan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-area">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-area">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for the charts
    const laptopLabels = {!! json_encode($chartData['labels']) !!};
    const scoreData = {!! json_encode($chartData['scores']) !!};
    const criteriaLabels = {!! json_encode($chartData['criteria']) !!};
    const radarData = {!! json_encode($chartData['radarData']) !!};
    
    // Radar Chart
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    const radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: criteriaLabels,
            datasets: [
                {
                    label: 'Top Laptop',
                    data: radarData[0],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(54, 162, 235, 1)',
                },
                {
                    label: '2nd Choice',
                    data: radarData[1],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(255, 99, 132, 1)',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            elements: {
                line: {
                    borderWidth: 2
                }
            },
            scales: {
                r: {
                    angleLines: {
                        display: true
                    },
                    suggestedMin: 0,
                    suggestedMax: 1
                }
            }
        }
    });
    
    // Bar Chart for Scores
    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: laptopLabels.slice(0, 5),
            datasets: [{
                label: 'Score',
                data: scoreData.slice(0, 5),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 1
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Top 5 Laptop Scores'
                }
            }
        }
    });
</script>
@endpush