<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatasetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'label' => 'required|in:organik,plastik,kertas,logam,residu',
            'file' => 'required|file|mimes:jpeg,png,jpg,zip|max:10240', // 10MB max
            'uploaded_by' => 'required|exists:penggunas,id',
        ];
    }
}
