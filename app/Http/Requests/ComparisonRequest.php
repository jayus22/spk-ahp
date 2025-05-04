<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComparisonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'criteria1_id' => 'required|exists:criterias,id',
            'criteria2_id' => 'required|exists:criterias,id|different:criteria1_id',
            'value' => 'required|numeric|min:1|max:9', // skala AHP
        ];
    }
}
