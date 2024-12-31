<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class UserResource extends Resource
{
    protected static ?string $model                = User::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon       = 'heroicon-o-users';

    public static function form(Form $form): Form {
        return $form
            ->schema([
                TextInput::make('name')
                         ->label(ucfirst(__('validation.attributes.name')))
                         ->required()
                         ->markAsRequired()
                         ->maxLength(255),
                TextInput::make('email')
                         ->label(ucfirst(__('validation.attributes.email')))
                         ->email()
                         ->unique(ignoreRecord: true)
                         ->required()
                         ->markAsRequired()
                         ->maxLength(255)
                         ->live(),
                TextInput::make('password')
                         ->label(ucfirst(__('validation.attributes.password')))
                         ->password()
                         ->revealable()
                         ->required()
                         ->markAsRequired()
                         ->maxLength(255)
                         ->hiddenOn('edit'),
                TextInput::make('profile_photo_path')
                         ->label(ucfirst(__('validation.attributes.photo')))
                         ->maxLength(2048),
                Select::make('roles')
                      ->relationship('roles', 'name')
                      ->multiple()
                      ->required()
                      ->markAsRequired()
                      ->placeholder('Selecciona los roles del usuario')
                      ->hint(new HtmlString('<a href="/admin/roles/create">Crear nuevo rol?</a>'))
                      ->hintColor('primary')
                      ->preload()
                      ->searchable(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                ImageColumn::make('avatarUrl')
                           ->label(ucfirst(__('validation.attributes.photo')))
                           ->circular(),
                TextColumn::make('name')
                          ->label(ucfirst(__('validation.attributes.name')))
                          ->searchable()
                          ->sortable(),
                TextColumn::make('email')
                          ->label(ucfirst(__('validation.attributes.email')))
                          ->searchable(),
                TextColumn::make('roles.name')
                          ->searchable()
                          ->badge(),
                IconColumn::make('email_verified_at')
                          ->label('Correo validado')
                          ->state(fn(User $user): bool => !is_null($user->email_verified_at))
                          ->boolean()
                          ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                          ->label(ucfirst(__('validation.attributes.created_at')))
                          ->dateTime()
                          ->sortable()
                          ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                          ->label(__('filament-shield::filament-shield.column.updated_at'))
                          ->dateTime()
                          ->sortable()
                          ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                             ->label(__('Correo verificado'))
                             ->nullable()
                             ->placeholder('Todos')
                             ->trueLabel('Verificados')
                             ->falseLabel('No verificados'),
                SelectFilter::make('Rol')
                            ->relationship('roles', 'name')
                            ->preload()
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    FilamentExportBulkAction::make('Exportar')->disablePreview()->failureNotification(Notification::make('No se pudo exportar los usuarios')),
                ]),
            ])
            ->striped()
            ->extremePaginationLinks();
    }

    public static function getRelations(): array {
        return [
        ];
    }

    public static function getPages(): array {
        return [
            'index'  => Pages\ListUsers::route('/'),
        ];
    }

    public static function getModelLabel(): string {
        return __('User'); // Singular
    }

    public static function getNavigationBadge(): ?string {
        return strval(static::getEloquentQuery()->count());
    }

    public static function getNavigationBadgeColor(): string|array|null {
        return \Filament\Support\Colors\Color::Neutral;

    }

    public static function getNavigationGroup(): ?string {
        return __('filament-shield::filament-shield.nav.group');
    }

}
