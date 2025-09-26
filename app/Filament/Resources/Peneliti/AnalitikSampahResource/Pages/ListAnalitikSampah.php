<?php

namespace App\Filament\Resources\Peneliti\AnalitikSampahResource\Pages;

use App\Filament\Resources\Peneliti\AnalitikSampahResource;
use App\Filament\Widgets\Admin\AnalitikSampahOverview;
use App\Filament\Widgets\Admin\SampahPerJenisChart;
use App\Filament\Widgets\Admin\SampahPerBulanChart;
use App\Filament\Widgets\Admin\SampahPerKecamatanChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListAnalitikSampah extends ListRecords
{
    use ExposesTableToWidgets;
    
    protected static string $resource = AnalitikSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh Data')
                ->icon('heroicon-o-arrow-path')
                ->action('$refresh'),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            AnalitikSampahOverview::class,
            SampahPerJenisChart::class,
            SampahPerBulanChart::class,
            SampahPerKecamatanChart::class,
        ];
    }
    
    public function getTitle(): string
    {
        return 'Analitik Sampah';
    }
    
    public function getSubheading(): ?string
    {
        return 'Dashboard analitik untuk monitoring dan analisis data sampah';
    }
}