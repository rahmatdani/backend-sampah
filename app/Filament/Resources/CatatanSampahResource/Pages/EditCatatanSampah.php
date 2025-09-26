<?php

namespace App\Filament\Resources\CatatanSampahResource\Pages;

use App\Filament\Resources\CatatanSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatatanSampah extends EditRecord
{
    protected static string $resource = CatatanSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}