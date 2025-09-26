<?php

namespace App\Filament\Widgets\Admin;

use App\Models\CatatanSampah;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SampahPerJenisChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Sampah Per Jenis';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = CatatanSampah::select('jenis_terdeteksi', DB::raw('count(*) as total'))
            ->whereNotNull('jenis_terdeteksi')
            ->groupBy('jenis_terdeteksi')
            ->pluck('total', 'jenis_terdeteksi')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Sampah per Jenis',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#10B981', // green
                        '#3B82F6', // blue
                        '#F59E0B', // amber
                        '#EF4444', // red
                        '#8B5CF6', // violet
                    ],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    
    public static function canView(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['peneliti', 'admin']);
    }
}