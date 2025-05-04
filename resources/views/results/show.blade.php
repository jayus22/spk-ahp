@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Pencarian</h1>
        <a href="{{ route('user.decision-form') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-search fa-sm text-white-50"></i> Cari Laptop Baru
        </a>
    </div>

    <!-- Search History Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Pencarian Laptop</h6>
        </div>
        <div class="card-body">
            @if($results->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Budget</th>
                                <th>Tujuan Penggunaan</th>
                                <th>Top Laptop</th>
                                <th>Skor</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td>{{ $result->created_at->format('d M Y H:i') }}</td>
                                    <td>Rp {{ number_format($result->min_budget, 0, ',', '.') }} - Rp {{ number_format($result->max_budget, 0, ',', '.') }}</td>
                                    <td>
                                        @foreach($result->purposes as $purpose)
                                            <span class="badge badge-secondary">{{ $purpose->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $result->topLaptop->brand }} {{ $result->topLaptop->model }}</td>
                                    <td>{{ number_format($result->top_score, 4) }}</td>
                                    <td>
                                        <a href="{{ route('user.results.show', $result->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('user.print-result', $result->id) }}" class="btn btn-success btn-sm" target="_blank">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                        <form action="{{ route('user.results.destroy', $result->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus riwayat pencarian ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $results->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-4x text-gray-300 mb-4"></i>
                    <h5>Belum Ada Riwayat Pencarian</h5>
                    <p class="text-muted">Anda belum pernah melakukan pencarian laptop sebelumnya.</p>
                    <a href="{{ route('user.decision-form') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-search"></i> Mulai Pencarian
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Card -->
    @if($results->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Statistik Pencarian</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Popular Brands Chart -->
                <div class="col-md-6">
                    <div class="chart-area mb-4">
                        <canvas id="brandChart"></canvas>
                    </div>
                </div>
                
                <!-- Budget Range Chart -->
                <div class="col-md-6">
                    <div class="chart-area mb-4">
                        <canvas id="budgetChart"></canvas>
                    </div>
                </div>
                
                <!-- Purpose Chart -->
                <div class="col-md-6">
                    <div class="chart-area">
                        <canvas id="purposeChart"></canvas>
                    </div>
                </div>
                
                <!-- Search Timeline Chart -->
                <div class="col-md-6">
                    <div class="chart-area">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            order: [[0, 'desc']]
        });
    });
    
    @if($results->count() > 0)
    // Chart Data
    const brandLabels = {!! json_encode($chartData['brands']['labels']) !!};
    const brandData = {!! json_encode($chartData['brands']['data']) !!};
    const budgetLabels = {!! json_encode($chartData['budgets']['labels']) !!};
    const budgetData = {!! json_encode($chartData['budgets']['data']) !!};
    const purposeLabels = {!! json_encode($chartData['purposes']['labels']) !!};
    const purposeData = {!! json_encode($chartData['purposes']['data']) !!};
    const timelineLabels = {!! json_encode($chartData['timeline']['labels']) !!};
    const timelineData = {!! json_encode($chartData['timeline']['data']) !!};
    
    // Brand Chart
    const brandCtx = document.getElementById('brandChart').getContext('2d');
    const brandChart = new Chart(brandCtx, {
        type: 'pie',
        data: {
            labels: brandLabels,
            datasets: [{
                data: brandData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Brand Laptop Terpilih'
                }
            }
        }
    });
    
    // Budget Chart
    const budgetCt