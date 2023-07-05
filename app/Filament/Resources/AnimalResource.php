<?php

namespace App\Filament\Resources;

use App\Enums\SexEnum;
use App\Enums\WeightUnitEnum;
use App\Filament\Resources\AnimalResource\Pages;
use App\Models\Animal;
use App\Models\Breed;
use App\Models\User;
use DB;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

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
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(trans('admin.fields.name'))
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
                            ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->name),
                        Forms\Components\Select::make('sex')
                            ->label(trans('admin.fields.sex'))
                            ->options(
                                collect(SexEnum::cases())
                                    ->mapWithKeys(fn (SexEnum $enum) => [$enum->value => $enum->getName()])
                            )
                            ->required(),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label(trans('admin.fields.birth_date'))
                            ->required(),
                    ]),
                Forms\Components\Fieldset::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('animal_type_id')
                            ->label(trans('admin.fields.type'))
                            ->relationship('type', 'name')
                            ->afterStateUpdated(function (callable $set) {
                                $set('custom_type_name', null);
                            })
                            ->reactive()
                            ->preload(),
                        Forms\Components\Select::make('breed_id')
                            ->label(trans('admin.fields.breed'))
                            ->relationship('breed', 'name')
                            ->options(fn (callable $get) => Breed::where(
                                'animal_type_id',
                                $get('animal_type_id')
                            )->pluck('name', 'id'))
                            ->afterStateUpdated(function (callable $set) {
                                $set('custom_breed_name', null);
                            })
                            ->disabled(fn (callable $get) => !$get('animal_type_id'))
                            ->reactive(),
                        Forms\Components\TextInput::make('custom_type_name')
                            ->label(trans('admin.fields.breed'))
                            ->hidden(fn (callable $get) => $get('animal_type_id')),
                        Forms\Components\TextInput::make('custom_breed_name')
                            ->label(trans('admin.fields.custom_breed_name'))
                            ->hidden(fn (callable $get) => $get('breed_id')),
                        Forms\Components\TextInput::make('breed_name')
                            ->label(trans('admin.fields.breed_name'))
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\TextInput::make('weight')
                            ->label(trans('admin.fields.weight'))
                            ->required(),
                        Forms\Components\Select::make('weight_unit')
                            ->label(trans('admin.fields.weight_unit'))
                            ->options(
                                collect(WeightUnitEnum::cases())
                                    ->mapWithKeys(fn (WeightUnitEnum $enum) => [$enum->value => $enum->getName()])
                            )
                            ->disablePlaceholderSelection()
                            ->default(WeightUnitEnum::Kg->value)
                            ->required(),
                    ]),
                Forms\Components\Toggle::make('metis')
                    ->label(trans('admin.fields.metis'))
                    ->required(),
                Forms\Components\Toggle::make('sterilised')
                    ->label(trans('admin.fields.sterilised'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner.name')
                    ->label(trans('admin.fields.owner'))
                    ->searchable(query: function (Builder $query, string $search) {
                        return $query->whereHas('owner', function (Builder $query) use ($search) {
                            $query
                                ->where(DB::raw('concat(first_name, " ", last_name)'), 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('type.name')
                    ->label(trans('admin.fields.type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('breed.name')
                    ->label(trans('admin.fields.breed'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('admin.fields.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('sex')
                    ->label(trans('admin.fields.sex')),
                Tables\Columns\TextColumn::make('custom_type_name')
                    ->label(trans('admin.fields.custom_type_name')),
                Tables\Columns\TextColumn::make('custom_breed_name')
                    ->label(trans('admin.fields.custom_breed_name')),
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

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'owner.name', 'owner.email', 'breed.name', 'type.name'];
    }
}
