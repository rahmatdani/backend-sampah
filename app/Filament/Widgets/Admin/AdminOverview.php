<?php

namespace App\Filament\Widgets\Admin;

use App\Models\CatatanSampah;
use App\Models\Kecamatan;
use App\Models\Pengguna;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Menghitung total data untuk setiap kategori
        $totalKecamatan = Kecamatan::count();
        $totalPengguna = Pengguna::count();

        // Menghitung total berat dan volume sampah
        $totalBeratSampah = CatatanSampah::sum('berat_kg');
        $totalVolumeSampah = CatatanSampah::sum('volume_terdeteksi_liter');

        return [
            Stat::make('Total Kecamatan', $totalKecamatan)
                ->description('Wilayah cakupan aplikasi')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success'),
            Stat::make('Total Pengguna', $totalPengguna)
                ->description('Pengguna aktif aplikasi')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Total Berat Sampah', number_format($totalBeratSampah, 2) . ' kg')
                ->description('Akumulasi berat sampah')
                ->descriptionIcon('heroicon-m-scale')
                ->color('primary'),
            Stat::make('Total Volume Sampah', number_format($totalVolumeSampah, 2) . ' L')
                ->description('Akumulasi volume sampah')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('secondary'),
        ];
    }
}