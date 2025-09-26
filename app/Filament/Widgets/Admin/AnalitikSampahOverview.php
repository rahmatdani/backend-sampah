<?php

namespace App\Filament\Widgets\Admin;

use App\Models\CatatanSampah;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalitikSampahOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Total Catatan Sampah', CatatanSampah::count())
                ->description('Jumlah total catatan sampah')
                ->descriptionIcon('heroicon-m-trash')
                ->color('success'),
                
            Stat::make('Total Volume (Liter)', number_format(CatatanSampah::sum('volume_terdeteksi_liter'), 1))
                ->description('Total volume sampah yang disetorkan')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('info'),
                
            Stat::make('Total Berat (Kg)', number_format(CatatanSampah::sum('berat_kg'), 1))
                ->description('Total berat sampah yang disetorkan')
                ->descriptionIcon('heroicon-m-scale')
                ->color('warning'),
                
            Stat::make('Data Tervalidasi', CatatanSampah::where('is_divalidasi', true)->count())
                ->description('Jumlah data yang sudah divalidasi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary'),
        ];
    }
    
    public static function canView(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['peneliti', 'admin']);
    }
}