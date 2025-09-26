<?php

namespace App\Filament\Resources\DatasetSampahResource\Pages;

use App\Filament\Resources\DatasetSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatasetSampah extends EditRecord
{
    protected static string $resource = DatasetSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}