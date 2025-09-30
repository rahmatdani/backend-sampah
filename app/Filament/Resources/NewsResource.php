<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';

    protected static string | \UnitEnum | null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Berita';

    protected static ?string $modelLabel = 'Berita';

    protected static ?string $pluralModelLabel = 'Berita';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canEdit($record): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canDelete($record): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canView(\Illuminate\Database\Eloquent\Model $record = null): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Berita')
                ->schema([
                    Forms\Components\TextInput::make('judul')
                        ->label('Judul Berita')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('kategori')
                        ->label('Kategori')
                        ->required()
                        ->maxLength(100),
                ])
                ->columns(2),

            Section::make('Konten')
                ->schema([
                    Forms\Components\RichEditor::make('konten')
                        ->label('Konten')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Gambar Unggulan')
                ->schema([
                    Forms\Components\FileUpload::make('foto_path')
                        ->label('Foto')
                        ->image()
                        ->disk('public')
                        ->directory('news-images')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->helperText('Unggah gambar (maksimal 2MB).'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->check() || auth()->user()->role !== 'admin') {
                    $query->where('id', '<', 0);
                }
            })
            ->filters([])
            ->recordActions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
