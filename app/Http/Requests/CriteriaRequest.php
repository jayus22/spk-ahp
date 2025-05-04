<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'weight' => 'required|numeric|min:0|max:1', // optional if using manual weight
            'type' => 'required|in:benefit,cost',
        ];
    }
}
