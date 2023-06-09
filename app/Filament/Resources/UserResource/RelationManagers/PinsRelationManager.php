<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Pin;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class PinsRelationManager extends RelationManager
{
    protected static string $relationship = 'pins';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type_id')
                    ->relationship('type', 'name')
                    ->columnSpanFull(),
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('latitude')
                        ->required(),
                    Forms\Components\TextInput::make('longitude')
                        ->required(),
                ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type.name')
                    ->label(trans('admin.fields.pin_type')),
                Tables\Columns\TextColumn::make('coordinates')
                    ->getStateUsing(fn (Pin $record) => "$record->latitude@$record->longitude"),
                Tables\Columns\TextColumn::make('reviews_avg_rating')
                    ->label(trans('admin.fields.rating'))
                    ->avg('reviews', 'rating')
                    ->formatStateUsing(fn (float $state) => number_format($state, 2)),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
