<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaptopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'ram' => 'required|numeric|min:1',
            'storage' => 'required|numeric|min:1',
        ];
    }
}
