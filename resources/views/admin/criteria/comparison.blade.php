@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Perbandingan Kriteria (AHP)</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Matrix Perbandingan Berpasangan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.criteria.comparison.store') }}" method="POST">
                @csrf
                <div class="table-responsive mb-4">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Kriteria</th>
                                @foreach($criteria as $criterion)
                                    <th class="text-center">{{ $criterion->code }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criteria as $row)
                                <tr>
                                    <td class="font-weight-bold">{{ $row->code }} ({{ $row->name }})</td>
                                    @foreach($criteria as $col)
                                        <td class="text-center">
                                            @if($row->id == $col->id)
                                                <input type="text" class="form-control form-control-sm text-center bg-light" value="1" readonly>
                                            @elseif(isset($comparisonValues[$row->id][$col->id]))
                                                <input type="number" class="form-control form-control-sm text-center comparison-value" 
                                                    name="comparison[{{ $row->id }}][{{ $col->id }}]" 
                                                    step="0.01" 
                                                    min="0.11" 
                                                    max="9" 
                                                    value="{{ $comparisonValues[$row->id][$col->id] }}" 
                                                    required>
                                            @else
                                                <input type="text" class="form-control form-control-sm text-center bg-light reciprocal-value" 
                                                    data-source="{{ $col->id }}-{{ $row->id }}" 
                                                    readonly>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Skala Perbandingan</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Nilai</th>
                                                <th>Definisi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Sama penting</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Sedikit lebih penting</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Lebih penting</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Sangat lebih penting</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Mutlak lebih penting</td>
                                            </tr>
                                            <tr>
                                                <td>2,4,6,8</td>
                                                <td>Nilai tengah</td>
                                            </tr>
                                            <tr>
                                                <td>1/2, 1/3, dst</td>
                                                <td>Kebalikan (reciprocal)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        @if(isset($consistencyRatio))
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Konsistensi</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <th>Consistency Index (CI)</th>
                                                <td>{{ number_format($consistencyIndex, 4) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Random Index (RI)</th>
                                                <td>{{ number_format($randomIndex, 4) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Consistency Ratio (CR)</th>
                                                <td>
                                                    {{ number_format($consistencyRatio, 4) }}
                                                    @if($consistencyRatio <= 0.1)
                                                        <span class="text-success ml-2">
                                                            <i class="fas fa-check-circle"></i> Konsisten
                                                        </span>
                                                    @else
                                                        <span class="text-danger ml-2">
                                                            <i class="fas fa-times-circle"></i> Tidak Konsisten
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perbandingan
                    </button>
                    <button type="submit" name="calculate_only" value="1" class="btn btn-info">
                        <i class="fas fa-calculator"></i> Hitung Konsistensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Menghitung nilai reciprocal otomatis
        $('.comparison-value').on('input', function() {
            let rowId = $(this).attr('name').match(/comparison\[(\d+)\]\[(\d+)\]/)[1];
            let colId = $(this).attr('name').match(/comparison\[(\d+)\]\[(\d+)\]/)[2];
            let value = parseFloat($(this).val()) || 0;
            
            // Mencari reciprocal input
            let reciprocalField = $('.reciprocal-value[data-source="' + rowId + '-' + colId + '"]');
            
            if (value > 0) {
                let reciprocalValue = (1 / value).toFixed(4);
                reciprocalField.val(reciprocalValue);
            } else {
                reciprocalField.val('');
            }
        });
        
        // Trigger untuk mengisi nilai reciprocal pada awal load
        $('.comparison-value').trigger('input');
    });
</script>
@endpush