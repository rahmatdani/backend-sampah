<?php

namespace App\Filament\Widgets\Admin;

use App\Models\CatatanSampah;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SampahPerKecamatanChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Sampah Per Kecamatan';
    
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $data = CatatanSampah::join('kecamatans', 'catatan_sampahs.kecamatan_id', '=', 'kecamatans.id')
            ->select('kecamatans.nama', DB::raw('count(*) as total'), DB::raw('SUM(volume_terdeteksi_liter) as volume'))
            ->groupBy('kecamatans.nama')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Catatan',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#10B981',
                ],
                [
                    'label' => 'Total Volume (L)',
                    'data' => $data->pluck('volume')->toArray(),
                    'backgroundColor' => '#3B82F6',
                ],
            ],
            'labels' => $data->pluck('nama')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    public static function canView(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['peneliti', 'admin']);
    }
}