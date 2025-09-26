<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatatanSampahResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pengguna_id' => $this->pengguna->id,
            'pengguna' => [
                'id' => $this->pengguna->id,
                'nama' => $this->pengguna->nama,
                'email' => $this->pengguna->email,
            ],
            'kecamatan_id' => $this->kecamatan->id,
            'kecamatan' => [
                'id' => $this->kecamatan->id,
                'nama' => $this->kecamatan->nama,
            ],
            'jenis_terdeteksi' => $this->jenis_terdeteksi,
            'volume_terdeteksi_liter' => $this->volume_terdeteksi_liter,
            'berat_kg' => $this->berat_kg,
            'foto_path' => $this->foto_path,
            'waktu_setoran' => $this->waktu_setoran,
            'is_divalidasi' => $this->is_divalidasi,
            'points_diberikan' => $this->points_diberikan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
