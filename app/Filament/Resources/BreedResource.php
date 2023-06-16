<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BreedResource\Pages;
use App\Models\Breed;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BreedResource extends Resource
{
    protected static ?string $model = Breed::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static function getNavigationGroup(): ?string
    {
        return trans('admin.nav_groups.settings');
    }

    protected static function getNavigationLabel(): string
    {
        return trans('admin.nav.breeds');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('admin.nav.breeds');
    }

    public static function getLabel(): ?string
    {
        return strtolower(trans('admin.fields.breed'));
    }

    public static function getBreadcrumb(): string
    {
        return trans('admin.nav.breeds');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('animal_type_id')
                    ->label(trans('admin.fields.animal_type'))
                    ->disablePlaceholderSelection()
                    ->searchable()
                    ->preload()
                    ->relationship('type', 'name')
                    ->required(),
                Forms\Components\Fieldset::make(trans('admin.fields.name'))
                    ->schema(
                        collect(config('app.available_locales'))
                            ->map(fn(string $locale) => Forms\Components\TextInput::make('name.'.$locale)->required())
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
                Tables\Columns\TextColumn::make('type.name')
                    ->label(trans('admin.fields.animal_type')),
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('admin.fields.name')),
                Tables\Columns\IconColumn::make('is_visible')
                    ->alignCenter()
                    ->label(trans('admin.fields.is_visible'))
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make(trans('admin.fields.animal_type'))
                    ->relationship('type', 'name'),
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
            'index' => Pages\ManageBreeds::route('/'),
        ];
    }
}
