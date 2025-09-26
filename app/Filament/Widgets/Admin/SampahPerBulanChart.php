<?php

namespace App\Filament\Widgets\Admin;

use App\Models\CatatanSampah;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SampahPerBulanChart extends ChartWidget
{
    protected ?string $heading = 'Tren Sampah Per Bulan';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = CatatanSampah::select(
                DB::raw('DATE_FORMAT(waktu_setoran, "%Y-%m") as bulan'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(volume_terdeteksi_liter) as volume'),
                DB::raw('SUM(berat_kg) as berat')
            )
            ->whereNotNull('waktu_setoran')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->limit(12)
            ->get();

        $labels = $data->pluck('bulan')->map(function ($bulan) {
            return Carbon::createFromFormat('Y-m', $bulan)->format('M Y');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Catatan',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
                [
                    'label' => 'Volume (L)',
                    'data' => $data->pluck('volume')->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    public static function canView(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['peneliti', 'admin']);
    }
}