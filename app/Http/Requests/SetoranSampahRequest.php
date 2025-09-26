<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetoranSampahRequest extends FormRequest
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
            'jenis_sampah' => 'required|string|in:Organik,Plastik,Kertas,Logam,Residu',
            'volume_liter' => 'required|numeric|min:0',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jenis_sampah.required' => 'Jenis sampah harus diisi.',
            'jenis_sampah.in' => 'Jenis sampah harus salah satu dari: Organik, Plastik, Kertas, Logam, Residu.',
            'volume_liter.required' => 'Volume liter harus diisi.',
            'volume_liter.numeric' => 'Volume liter harus berupa angka.',
            'volume_liter.min' => 'Volume liter minimal 0.',
            'foto.required' => 'Foto bukti setoran harus diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}