<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CheckSheetResource\Pages;
use App\Filament\Resources\CheckSheetResource\RelationManagers;
use App\Infolists\Components\ViewItems;
use App\Models\CheckSheet;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CheckSheetResource extends Resource
{
    protected static ?string $model = CheckSheet::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $recordTitleAttribute = 'equipment_tag';

    public static function getNavigationGroup(): ?string {
        return 'Hojas de chequeo';
    }

    public static function infolist(Infolist $infolist): Infolist {
        return $infolist
            ->schema([
                Grid::make()->schema([
                    Section::make(__('CheckSheet data'))
                           ->icon('heroicon-o-document')
                           ->schema([
                               TextEntry::make('name')->translateLabel(),
                               TextEntry::make('notes')->html()->translateLabel(),
                               Grid::make(3)->schema([
                                   IconEntry::make('is_published')
                                            ->label('Publicado')
                                            ->boolean()
                                            ->columnSpan(1),
                                   TextEntry::make('created_at')->label('Creado')
                                            ->since()
                                            ->dateTimeTooltip()
                                            ->columnSpan(1)->badge(),
                                   TextEntry::make('updated_at')->label('Actualizado')
                                            ->columnSpan(1)->since()
                                            ->dateTimeTooltip()->badge()
                               ])
                           ])->columnSpan(1),
                    Section::make(__('Equipment data'))->columnSpan(1)
                           ->icon('heroicon-o-wrench-screwdriver')
                           ->schema([
                               TextEntry::make('equipment_area')
                                        ->label('Area')
                                        ->translateLabel(),
                               Grid::make()->schema([
                                   TextEntry::make('equipment_tag')
                                            ->label('Tag')
                                            ->columnSpan(1),
                                   TextEntry::make('equipment_name')
                                            ->label('Name')
                                            ->translateLabel()
                               ])
                           ]),
                    Section::make('Items')->columnSpanFull()
                           ->icon('heroicon-o-table-cells')
                           ->schema([
                               ViewItems::make('items')->hiddenLabel()
                           ])
                ])
            ]);
    }

    public static function table(Table $table): Table {
        return $table->searchable()->striped()
                     ->columns([
                         TextColumn::make('name')->translateLabel()->searchable()->sortable(),
                         TextColumn::make('equipment_tag')->label('Tag')->searchable()->sortable(),
                         TextColumn::make('equipment_area')
                                   ->label('Area')
                                   ->searchable()
                                   ->sortable(),
                         TextColumn::make('equipment_name')->label("Equipo")->searchable()
                                   ->sortable(),
                         TextColumn::make('Items')->badge()->color(Color::Neutral)
                                   ->state(fn(CheckSheet $checkSheet) => $checkSheet->items->count()),
                         ToggleColumn::make('is_published')->label("Publicada"),
                         TextColumn::make('notes')->html()
                                   ->label('Observaciones')
                                   ->toggleable(isToggledHiddenByDefault: true),
                         TextColumn::make('created_at')
                                   ->sortable()
                                   ->translateLabel()
                                   ->badge()
                                   ->dateTime("d M Y H:i")
                                   ->toggleable(isToggledHiddenByDefault: true),
                         TextColumn::make('updated_at')->sortable()
                                   ->translateLabel()
                                   ->badge()
                                   ->dateTime('d M Y H:i')
                                   ->toggleable(isToggledHiddenByDefault: true),
                         TextColumn::make('createdBy.name')
                                   ->translateLabel()
                                   ->toggleable(isToggledHiddenByDefault: true),
                         TextColumn::make('updatedBy.name')
                                   ->translateLabel()
                                   ->toggleable(isToggledHiddenByDefault: true)
                     ])
                     ->filters([
                         //
                     ])
                     ->actions([
                         Tables\Actions\ViewAction::make()->slideOver(),
                         Tables\Actions\EditAction::make(),
                         Tables\Actions\DeleteAction::make(),
                         Tables\Actions\Action::make('Historial')
                                              ->url(fn(CheckSheet $record): string => CheckSheetResource::getUrl('history', ['record' => $record]))
                                              ->icon("heroicon-o-calendar")
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

    public static function getPluralModelLabel(): string {
        return __('CheckSheets');
    }

    public static function getModelLabel(): string {
        return __('CheckSheet');
    }

    public static function getPages(): array {
        return [
            'index'   => Pages\ListCheckSheets::route('/'),
            'create'  => Pages\CreateCheckSheet::route('/create'),
            'edit'    => Pages\EditCheckSheet::route('/{record}/edit'),
            'view'    => Pages\ViewCheckSheet::route('/{record}'),
            'history' => Pages\HistoryCheckSheet::route('/{record}/history')
        ];
    }
}
