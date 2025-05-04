@extends('layouts.admin')

@section('title', 'Tambah Laptop Baru')

@section('styles')
<style>
    .form-group {
        margin-bottom: 15px;
    }
    .badge-info {
        margin-right: 5px;
    }
</style>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2><i class="fas fa-plus"></i> Tambah Laptop Baru</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ url('/admin/laptops') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/admin/laptops') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Nama Laptop <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="brand" class="form-label">Merek <span class="text-danger">*</span></label>
                                <select name="brand" id="brand" class="form-select @error('brand') is-invalid @enderror" required>
                                    <option value="">Pilih Merek</option>
                                    <option value="Acer" {{ old('brand') == 'Acer' ? 'selected' : '' }}>Acer</option>
                                    <option value="Apple" {{ old('brand') == 'Apple' ? 'selected' : '' }}>Apple</option>
                                    <option value="Asus" {{ old('brand') == 'Asus' ? 'selected' : '' }}>Asus</option>
                                    <option value="Dell" {{ old('brand') == 'Dell' ? 'selected' : '' }}>Dell</option>
                                    <option value="HP" {{ old('brand') == 'HP' ? 'selected' : '' }}>HP</option>
                                    <option value="Lenovo" {{ old('brand') == 'Lenovo' ? 'selected' : '' }}>Lenovo</option>
                                    <option value="MSI" {{ old('brand') == 'MSI' ? 'selected' : '' }}>MSI</option>
                                    <option value="Samsung" {{ old('brand') == 'Samsung' ? 'selected' : '' }}>Samsung</option>
                                    <option value="Lainnya" {{ old('brand') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="processor" class="form-label">Prosesor <span class="text-danger">*</span></label>
                                <input type="text" name="processor" id="processor" class="form-control @error('processor') is-invalid @enderror" value="{{ old('processor') }}" required>
                                @error('processor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="ram" class="form-label">RAM (GB) <span class="text-danger">*</span></label>
                                <input type="number" name="ram" id="ram" class="form-control @error('ram') is-invalid @enderror" value="{{ old('ram') }}" required>
                                @error('ram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="storage" class="form-label">Kapasitas Penyimpanan (GB) <span class="text-danger">*</span></label>
                                <input type="number" name="storage" id="storage" class="form-control @error('storage') is-invalid @enderror" value="{{ old('storage') }}" required>
                                @error('storage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="storage_type" class="form-label">Tipe Penyimpanan <span class="text-danger">*</span></label>
                                <select name="storage_type" id="storage_type" class="form-select @error('storage_type') is-invalid @enderror" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="SSD" {{ old('storage_type') == 'SSD' ? 'selected' : '' }}>SSD</option>
                                    <option value="HDD" {{ old('storage_type') == 'HDD' ? 'selected' : '' }}>HDD</option>
                                    <option value="SSD + HDD" {{ old('storage_type') == 'SSD + HDD' ? 'selected' : '' }}>SSD + HDD</option>
                                </select>
                                @error('storage_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="display_size" class="form-label">Ukuran Layar (inci) <span class="text-danger">*</span></label>
                                <input type="number" step="0.1" name="display_size" id="display_size" class="form-control @error('display_size') is-invalid @enderror" value="{{ old('display_size') }}" required>
                                @error('display_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gpu" class="form-label">GPU (Kartu Grafis)</label>
                                <input type="text" name="gpu" id="gpu" class="form-control @error('gpu') is-invalid @enderror" value="{{ old('gpu') }}">
                                @error('gpu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image" class="form-label">Gambar Laptop</label>
                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                <small class="text-muted">Format yang diperbolehkan: JPG, JPEG, PNG. Ukuran maksimal: 2MB</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Fitur Tambahan</label>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="backlit_keyboard" name="features[]" value="backlit_keyboard" {{ in_array('backlit_keyboard', old('features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="backlit_keyboard">Keyboard Backlit</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="fingerprint" name="features[]" value="fingerprint" {{ in_array('fingerprint', old('features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fingerprint">Fingerprint</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="touchscreen" name="features[]" value="touchscreen" {{ in_array('touchscreen', old('features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="touchscreen">Layar Sentuh</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="facial_recognition" name="features[]" value="facial_recognition" {{ in_array('facial_recognition', old('features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="facial_recognition">Pengenalan Wajah</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Laptop
                            </button>
                            <a href="{{ url('/admin/laptops') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Format harga
    document.getElementById('price').addEventListener('input', function(e) {
        // Hapus karakter non-numerik
        let value = this.value.replace(/\D/g, '');
        this.value = value;
    });
</script>
@endsection