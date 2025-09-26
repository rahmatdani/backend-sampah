<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatatanSampahRequest extends FormRequest
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
            'pengguna_id' => 'required|exists:penggunas,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'jenis_terdeteksi' => 'nullable|string|max:255',
            'volume_terdeteksi_liter' => 'nullable|numeric',
            'berat_kg' => 'nullable|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Increased to 10MB
            'waktu_setoran' => 'nullable|date',
        ];
    }
}
