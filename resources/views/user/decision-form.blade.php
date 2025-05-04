@extends('layouts.user')

@section('title', 'Make a Decision')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="mb-6 text-2xl font-bold">Laptop Selection Decision Helper</h1>

        @if (session('success'))
            <div class="mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-lg bg-white p-6 shadow-md">
            <form action="{{ route('user.decisions.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Step 1: Basic Information -->
                <div class="border-b pb-4">
                    <h2 class="mb-4 text-xl font-semibold">Step 1: Basic Information</h2>

                    <div class="mb-4">
                        <label for="title" class="mb-2 block font-bold text-gray-700">Title your decision</label>
                        <input type="text" name="title" id="title"
                            placeholder="e.g., Finding the best laptop for college" value="{{ old('title') }}"
                            class="@error('title') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="mb-2 block font-bold text-gray-700">Describe your needs
                            (optional)</label>
                        <textarea name="description" id="description" rows="3"
                            placeholder="What will you use this laptop for? Any specific requirements?"
                            class="@error('description') border-red-500 @enderror w-full rounded border px-3 py-2 text-gray-700">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Step 2: Select Laptops to Compare -->
                <div class="border-b pb-4">
                    <h2 class="mb-4 text-xl font-semibold">Step 2: Select Laptops to Compare</h2>
                    <p class="mb-4 text-gray-600">Choose at least 2 laptops to compare (maximum 5)</p>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($laptops as $laptop)
                            <div class="rounded border p-4 hover:bg-gray-50">
                                <div class="flex items-start">
                                    <div class="mr-3">
                                        <input type="checkbox" name="laptops[]" id="laptop-{{ $laptop->id }}"
                                            value="{{ $laptop->id }}" class="h-5 w-5"
                                            {{ in_array($laptop->id, old('laptops', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div>
                                        <label for="laptop-{{ $laptop->id }}"
                                            class="block font-bold">{{ $laptop->brand }} {{ $laptop->model }}</label>
                                        <div class="text-sm text-gray-600">
                                            <p>Processor: {{ $laptop->processor }}</p>
                                            <p>RAM: {{ $laptop->ram }}GB</p>
                                            <p>Price: ${{ number_format($laptop->price, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('laptops')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Step 3: Select and Rate Criteria -->
                <div class="pb-4">
                    <h2 class="mb-4 text-xl font-semibold">Step 3: Select and Rate Criteria Importance</h2>
                    <p class="mb-4 text-gray-600">Choose the criteria that matter to you and rate their importance</p>

                    <div class="space-y-4">
                        @foreach ($criteria as $criterion)
                            <div class="rounded border p-4 hover:bg-gray-50">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <div class="mb-2 flex items-start sm:mb-0">
                                        <div class="mr-3">
                                            <input type="checkbox" name="criteria[]" id="criteria-{{ $criterion->id }}"
                                                value="{{ $criterion->id }}" class="criteria-checkbox h-5 w-5"
                                                {{ in_array($criterion->id, old('criteria', [])) ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <label for="criteria-{{ $criterion->id }}"
                                                class="block font-bold">{{ $criterion->name }}</label>
                                            <p class="text-sm text-gray-600">{{ $criterion->description }}</p>
                                        </div>
                                    </div>

                                    <div class="w-full sm:w-48">
                                        <select name="importance[{{ $criterion->id }}]"
                                            id="importance-{{ $criterion->id }}"
                                            class="criteria-importance w-full rounded border px-3 py-2 text-gray-700"
                                            {{ !in_array($criterion->id, old('criteria', [])) ? 'disabled' : '' }}>
                                            <option value="">Select importance</option>
                                            <option value="1"
                                                {{ old("importance.$criterion->id") == '1' ? 'selected' : '' }}>Low
                                                importance</option>
                                            <option value="3"
                                                {{ old("importance.$criterion->id") == '3' ? 'selected' : '' }}>Moderate
                                                importance</option>
                                            <option value="5"
                                                {{ old("importance.$criterion->id") == '5' ? 'selected' : '' }}>Strong
                                                importance</option>
                                            <option value="7"
                                                {{ old("importance.$criterion->id") == '7' ? 'selected' : '' }}>Very strong
                                                importance</option>
                                            <option value="9"
                                                {{ old("importance.$criterion->id") == '9' ? 'selected' : '' }}>Extreme
                                                importance</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('criteria')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    @error('importance.*')
                        <p class="mt-2 text-sm text-red-500">Please select importance level for all selected criteria</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="focus:shadow-outline rounded-lg bg-blue-500 px-6 py-3 font-bold text-white hover:bg-blue-600 focus:outline-none">
                        Calculate Best Option
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle checkbox changes to enable/disable importance dropdown
            const criteriaCheckboxes = document.querySelectorAll('.criteria-checkbox');

            criteriaCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const criteriaId = this.id.split('-')[1];
                    const importanceDropdown = document.getElementById('importance-' + criteriaId);

                    if (this.checked) {
                        importanceDropdown.disabled = false;
                        importanceDropdown.required = true;
                    } else {
                        importanceDropdown.disabled = true;
                        importanceDropdown.required = false;
                        importanceDropdown.selectedIndex = 0;
                    }
                });
            });
        });
    </script>
@endpush
