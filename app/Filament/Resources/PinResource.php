<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PinResource\Pages;
use App\Models\Pin;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class PinResource extends Resource
{
    protected static ?string $model = Pin::class;

    protected static ?string $navigationIcon = 'heroicon-o-location-marker';

    protected static function getNavigationGroup(): ?string
    {
        return trans('admin.nav_groups.users');
    }

    protected static function getNavigationLabel(): string
    {
        return trans('admin.nav.pins');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('admin.nav.pins');
    }

    public static function getLabel(): ?string
    {
        return strtolower(trans('admin.fields.pin'));
    }

    public static function getBreadcrumb(): string
    {
        return trans('admin.nav.pins');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(trans('admin.fields.user'))
                    ->required()
                    ->searchable()
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('type_id')
                    ->relationship('type', 'name'),
                Forms\Components\TextInput::make('latitude')
                    ->required(),
                Forms\Components\TextInput::make('longitude')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('type.name'),
                Tables\Columns\TextColumn::make('coordinates')
                    ->getStateUsing(fn(Pin $record) => "$record->latitude@$record->longitude"),
            ])
            ->filters([
                Tables\Filters\Filter::make('position')
                    ->form([
                        Forms\Components\TextInput::make('latitude')
                            ->label(trans('admin.fields.latitude'))
                            ->numeric(),
                        Forms\Components\TextInput::make('longitude')
                            ->label(trans('admin.fields.longitude'))
                            ->numeric(),
                        Forms\Components\TextInput::make('radius')
                            ->label(trans('admin.fields.radius'))
                            ->numeric()
                            ->step(1)
                            ->minValue(1),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['latitude'] && $data['longitude'] && $data['radius'],
                                fn(Builder $query): Builder => $query->radius(
                                    $data['latitude'],
                                    $data['longitude'],
                                    $data['radius']
                                )
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPins::route('/'),
            'create' => Pages\CreatePin::route('/create'),
            'edit' => Pages\EditPin::route('/{record}/edit'),
        ];
    }
}
