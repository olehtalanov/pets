<?php

namespace App\Filament\Resources;

use App\Enums\Animal\SexEnum;
use App\Enums\Animal\WeightUnitEnum;
use App\Filament\Resources\AnimalResource\Pages;
use App\Filament\Resources\AnimalResource\RelationManagers;
use App\Models\Animal;
use App\Models\Breed;
use App\Models\User;
use DB;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class AnimalResource extends Resource
{
    protected static ?string $model = Animal::class;

    protected static ?string $navigationIcon = 'heroicon-o-emoji-happy';

    protected static function getNavigationGroup(): ?string
    {
        return trans('admin.nav_groups.users');
    }

    protected static function getNavigationLabel(): string
    {
        return trans('admin.nav.animals');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('admin.nav.animals');
    }

    public static function getLabel(): ?string
    {
        return strtolower(trans('admin.fields.animal'));
    }

    public static function getBreadcrumb(): string
    {
        return trans('admin.nav.animals');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->label(trans('admin.fields.owner'))
                    ->required()
                    ->searchable()
                    ->relationship('owner', 'name')
                    ->getSearchResultsUsing(function (string $search) {
                        return User::query()
                            ->where(DB::raw('concat(first_name, " ", last_name)'), 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->selectRaw('*, concat(first_name, " ", last_name) as name')
                            ->limit(50)
                            ->pluck('name', 'id');
                    })
                    ->getOptionLabelUsing(fn($value): ?string => User::find($value)?->name),
                Forms\Components\Select::make('sex')
                    ->options(
                        collect(SexEnum::cases())
                            ->mapWithKeys(fn(SexEnum $enum) => [$enum->value => $enum->getName()])
                    )
                    ->required(),
                Forms\Components\DatePicker::make('birth_date')
                    ->required(),
                Forms\Components\Card::make([
                    Forms\Components\Select::make('animal_type_id')
                        ->relationship('type', 'name')
                        ->reactive()
                        ->preload(),
                    Forms\Components\Select::make('breed_id')
                        ->relationship('breed', 'name')
                        ->reactive()
                        ->options(fn(callable $get) => Breed::where(
                            'animal_type_id',
                            $get('animal_type_id')
                        )->pluck('name', 'id'))
                        ->disabled(fn(callable $get) => !$get('animal_type_id')),
                    Forms\Components\TextInput::make('custom_type_name')
                        ->hidden(fn(callable $get) => $get('animal_type_id')),
                    Forms\Components\TextInput::make('custom_breed_name')
                        ->hidden(fn(callable $get) => $get('breed_id')),
                    Forms\Components\TextInput::make('breed_name')
                        ->maxLength(255),
                    Forms\Components\Toggle::make('metis')
                        ->required(),
                ]),
                Forms\Components\TextInput::make('weight')
                    ->required(),
                Forms\Components\Select::make('weight_unit')
                    ->options(
                        collect(WeightUnitEnum::cases())
                            ->mapWithKeys(fn(WeightUnitEnum $enum) => [$enum->value => $enum->getName()])
                    )
                    ->disablePlaceholderSelection()
                    ->default(WeightUnitEnum::Kg->value)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('breed.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sex'),
                Tables\Columns\TextColumn::make('custom_type_name'),
                Tables\Columns\TextColumn::make('custom_breed_name'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimals::route('/'),
            'create' => Pages\CreateAnimal::route('/create'),
            'edit' => Pages\EditAnimal::route('/{record}/edit'),
        ];
    }
}
