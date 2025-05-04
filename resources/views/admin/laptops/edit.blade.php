@extends('layouts.admin')

@section('title', 'Edit Laptop')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Edit Laptop</h1>
            <a href="{{ route('admin.laptops.index') }}" class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                Back to List
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg bg-white p-6 shadow-md">
            <form action="{{ route('admin.laptops.update', $laptop) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="brand" class="mb-2 block font-bold text-gray-700">Brand</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand', $laptop->brand) }}"
                        class="@error('brand') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="model" class="mb-2 block font-bold text-gray-700">Model</label>
                    <input type="text" name="model" id="model" value="{{ old('model', $laptop->model) }}"
                        class="@error('model') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('model')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="processor" class="mb-2 block font-bold text-gray-700">Processor</label>
                    <input type="text" name="processor" id="processor" value="{{ old('processor', $laptop->processor) }}"
                        class="@error('processor') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('processor')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="ram" class="mb-2 block font-bold text-gray-700">RAM (GB)</label>
                    <input type="number" name="ram" id="ram" value="{{ old('ram', $laptop->ram) }}"
                        class="@error('ram') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('ram')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="storage" class="mb-2 block font-bold text-gray-700">Storage (GB)</label>
                    <input type="number" name="storage" id="storage" value="{{ old('storage', $laptop->storage) }}"
                        class="@error('storage') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('storage')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gpu" class="mb-2 block font-bold text-gray-700">GPU</label>
                    <input type="text" name="gpu" id="gpu" value="{{ old('gpu', $laptop->gpu) }}"
                        class="@error('gpu') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('gpu')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="display_size" class="mb-2 block font-bold text-gray-700">Display Size (inches)</label>
                    <input type="number" step="0.1" name="display_size" id="display_size"
                        value="{{ old('display_size', $laptop->display_size) }}"
                        class="@error('display_size') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('display_size')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="display_resolution" class="mb-2 block font-bold text-gray-700">Display Resolution</label>
                    <input type="text" name="display_resolution" id="display_resolution"
                        value="{{ old('display_resolution', $laptop->display_resolution) }}"
                        class="@error('display_resolution') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('display_resolution')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="weight" class="mb-2 block font-bold text-gray-700">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight" id="weight"
                        value="{{ old('weight', $laptop->weight) }}"
                        class="@error('weight') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('weight')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="battery_life" class="mb-2 block font-bold text-gray-700">Battery Life (hours)</label>
                    <input type="number" step="0.5" name="battery_life" id="battery_life"
                        value="{{ old('battery_life', $laptop->battery_life) }}"
                        class="@error('battery_life') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('battery_life')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="price" class="mb-2 block font-bold text-gray-700">Price</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $laptop->price) }}"
                        class="@error('price') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('price')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="mb-2 block font-bold text-gray-700">Image</label>
                    @if ($laptop->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $laptop->image) }}"
                                alt="{{ $laptop->brand . ' ' . $laptop->model }}" class="h-32">
                        </div>
                    @endif
                    <input type="file" name="image" id="image"
                        class="@error('image') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                    @error('image')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="mb-2 block font-bold text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="@error('description') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">{{ old('description', $laptop->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        Update Laptop
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
