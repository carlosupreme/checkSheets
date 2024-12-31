<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CheckStatusResource\Pages;
use App\Filament\Resources\CheckStatusResource\RelationManagers;
use App\Models\CheckStatus;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use TomatoPHP\FilamentIcons\Components\IconPicker;

class CheckStatusResource extends Resource
{
    protected static ?string $model = CheckStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string {
        return 'Hojas de chequeo';
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Section::make("")
                       ->columns(3)
                       ->schema([
                           TextInput::make('name')
                                    ->live(onBlur: true)
                                    ->unique(ignoreRecord: true)
                                    ->translateLabel()
                                    ->required()
                                    ->maxLength(255),
                           ColorPicker::make('color')
                                      ->translateLabel()
                                      ->required()
                                      ->default("#000000"),
                           IconPicker::make('icon')
                                     ->searchable()
                                     ->translateLabel()
                                     ->preload()
                                     ->required(),

                           Textarea::make('description')
                                   ->translateLabel()
                                   ->columnSpanFull()
                                   ->nullable(),
                       ])
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                IconColumn::make('icon')
                          ->translateLabel()
                          ->icon(fn($record) => $record->icon)
                          ->color(fn(CheckStatus $record) => Color::hex($record->color)),
                TextColumn::make('name')
                          ->searchable()
                          ->sortable()
                          ->translateLabel(),
                TextColumn::make('description')
                          ->searchable()
                          ->sortable()
                          ->translateLabel(),
                TextColumn::make('createdBy.name')
                          ->toggleable(isToggledHiddenByDefault: true)
                          ->translateLabel()
                          ->searchable()
                          ->sortable(),
                TextColumn::make('updatedBy.name')
                          ->toggleable(isToggledHiddenByDefault: true)
                          ->translateLabel()
                          ->searchable()
                          ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->slideOver()->mutateFormDataUsing(function (array $data): array {
                    $data['updated_by'] = auth()->id();
                    return $data;
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getModelLabel(): string {
        return __('Check Status');
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListCheckStatuses::route('/'),
        ];
    }
}
