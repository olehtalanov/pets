<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PinTypeResource\Pages;
use App\Models\PinType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PinTypeResource extends Resource
{
    protected static ?string $model = PinType::class;

    protected static ?string $navigationIcon = 'iconsax-two-map-1';

    protected static function getNavigationGroup(): ?string
    {
        return trans('admin.nav_groups.settings');
    }

    protected static function getNavigationLabel(): string
    {
        return trans('admin.nav.pin_types');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('admin.nav.pin_types');
    }

    public static function getLabel(): ?string
    {
        return '';
    }

    public static function getBreadcrumb(): string
    {
        return trans('admin.nav.pin_types');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make(trans('admin.fields.name'))
                    ->schema(
                        collect(config('app.available_locales'))
                            ->map(fn (string $locale) => Forms\Components\TextInput::make('name.'.$locale)->required())
                            ->toArray()
                    ),
                Forms\Components\Toggle::make('is_visible')
                    ->label(trans('admin.fields.is_visible'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('admin.fields.name')),
                Tables\Columns\IconColumn::make('is_visible')
                    ->alignCenter()
                    ->label(trans('admin.fields.is_visible'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('pins_count')
                    ->alignCenter()
                    ->counts('pins'),
                Tables\Columns\TextColumn::make('users_count')
                    ->alignCenter()
                    ->counts('users'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePinTypes::route('/'),
        ];
    }
}
