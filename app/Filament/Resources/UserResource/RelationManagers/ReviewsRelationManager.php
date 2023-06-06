<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Pin;
use DB;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $recordTitleAttribute = 'rating';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pin_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label(trans('admin.fields.pin'))
                    ->relationship('pin', 'name', function (Builder $query) {
                        $query
                            ->select([
                                'pins.*',
                                DB::raw('concat(pins.latitude, "@", pins.longitude, " (", users.name, ")") as name'),
                            ])
                            ->leftJoin('users', static function (JoinClause $join) {
                                $join->on('pins.user_id', '=', 'users.id');
                            });
                    })
                    ->getSearchResultsUsing(function (string $search, RelationManager $livewire) {
                        return Pin::with('user')
                            ->select([
                                'pins.id as pid',
                                DB::raw('concat(pins.latitude, "@", pins.longitude, " (", users.name, ")") as name'),
                            ])
                            ->leftJoin('users', static function (JoinClause $join) use ($livewire) {
                                $join->on('pins.user_id', '=', 'users.id')
                                    ->where('users.id', '!=', $livewire->ownerRecord->getKey());
                            })
                            ->where('users.name', 'like', "%$search%")
                            ->limit(10)
                            ->pluck('name', 'pid');
                    })
                    ->columnSpan(2),
                Forms\Components\TextInput::make('rating')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(5),
                Forms\Components\Textarea::make('message')
                    ->label(trans('admin.fields.message'))
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reviewable.name')
                    ->label(trans('admin.fields.reviewable')),
                Tables\Columns\TextColumn::make('rating'),
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
