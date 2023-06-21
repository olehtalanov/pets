<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use DB;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static function getNavigationGroup(): ?string
    {
        return trans('admin.nav_groups.users');
    }

    protected static function getNavigationLabel(): string
    {
        return trans('admin.nav.reviews');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('admin.nav.reviews');
    }

    public static function getLabel(): ?string
    {
        return null;
    }

    public static function getBreadcrumb(): string
    {
        return trans('admin.nav.reviews');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(trans('admin.fields.reviewer'))
                    ->relationship('reviewer', 'name', fn (Builder $query) => $query->select(['users.*', DB::raw('CONCAT(first_name, last_name) as name')]))
                    ->required(),
                Forms\Components\Select::make('pin_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label(trans('admin.fields.pin'))
                    ->relationship('pin', 'name', function (Builder $query) {
                        $query
                            ->select([
                                'pins.*',
                                DB::raw('concat(pins.latitude, "@", pins.longitude, " (", users.first_name, " ", users.last_name, ")") as name'),
                            ])
                            ->leftJoin('users', static function (JoinClause $join) {
                                $join->on('pins.user_id', '=', 'users.id');
                            });
                    }),
                Forms\Components\Textarea::make('message')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('rating')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reviewer.name')
                    ->label(trans('admin.fields.reviewer')),
                Tables\Columns\TextColumn::make('pin.user.name')
                    ->label(trans('admin.fields.reviewable')),
                Tables\Columns\IconColumn::make('rating')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
