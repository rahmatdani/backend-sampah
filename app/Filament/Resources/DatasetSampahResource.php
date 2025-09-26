<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DatasetSampahResource\Pages;
use App\Filament\Resources\DatasetSampahResource\RelationManagers;
use App\Models\DatasetSampah;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;

class DatasetSampahResource extends Resource
{
    protected static ?string $model = DatasetSampah::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    protected static string | \UnitEnum | null $navigationGroup = 'Machine Learning';

    protected static ?string $recordTitleAttribute = 'label';

    protected static ?string $navigationLabel = 'Dataset Sampah';

    protected static ?string $modelLabel = 'Dataset Sampah';

    protected static ?string $pluralModelLabel = 'Dataset Sampah';
    
    public static function canView(\Illuminate\Database\Eloquent\Model $record = null): bool
    {
        // Log untuk debugging
        if (auth()->check()) {
            \Log::info('DatasetSampahResource canView check', [
                'user_role' => auth()->user()->role,
                'is_allowed' => in_array(auth()->user()->role, ['peneliti', 'admin'])
            ]);
        }
        
        // Hanya peneliti dan admin yang bisa mengakses resource ini
        return auth()->check() && in_array(auth()->user()->role, ['peneliti', 'admin']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Dataset')
                    ->schema([
                        Forms\Components\Select::make('label')
                            ->options([
                                'organik' => 'Organik',
                                'plastik' => 'Plastik',
                                'kertas' => 'Kertas',
                                'logam' => 'Logam',
                                'residu' => 'Residu',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('path_file')
                            ->label('Path File')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('uploaded_by')
                            ->relationship('pengguna', 'nama')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Jenis Sampah')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'organik' => 'success',
                        'plastik' => 'info',
                        'kertas' => 'warning',
                        'logam' => 'gray',
                        'residu' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('path_file')
                    ->label('Path File')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pengguna.nama')
                    ->label('Diupload Oleh')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diupload Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('label')
                    ->label('Jenis Sampah')
                    ->options([
                        'organik' => 'Organik',
                        'plastik' => 'Plastik',
                        'kertas' => 'Kertas',
                        'logam' => 'Logam',
                        'residu' => 'Residu',
                    ])
                    ->native(false),
            ])
            ->recordActions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatasetSampahs::route('/'),
            'create' => Pages\CreateDatasetSampah::route('/create'),
            'edit' => Pages\EditDatasetSampah::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        // Hanya pengguna dengan role peneliti atau admin yang bisa mengakses
        return auth()->check() && in_array(auth()->user()->role ?? '', ['peneliti', 'admin']);
    }

    public static function canCreate(): bool
    {
        // Baik peneliti maupun admin bisa membuat dataset baru
        return auth()->check() && in_array(auth()->user()->role, ['peneliti', 'admin']);
    }

    public static function canEdit($record): bool
    {
        // Baik peneliti maupun admin bisa mengedit dataset
        return auth()->check() && in_array(auth()->user()->role, ['peneliti', 'admin']);
    }

    public static function canDelete($record): bool
    {
        // Hanya admin yang bisa menghapus dataset
        return auth()->check() && auth()->user()->role === 'admin';
    }
}