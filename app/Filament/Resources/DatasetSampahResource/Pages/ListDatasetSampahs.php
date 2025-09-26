<?php

namespace App\Filament\Resources\DatasetSampahResource\Pages;

use App\Filament\Resources\DatasetSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatasetSampahs extends ListRecords
{
    protected static string $resource = DatasetSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}