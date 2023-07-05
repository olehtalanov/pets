<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PinResource\Pages;
use App\Models\Pin;
use App\Models\User;
use DB;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Str;

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
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label(trans('admin.fields.user'))
                            ->required()
                            ->searchable()
                            ->relationship('user', 'name')
                            ->getSearchResultsUsing(function (string $search) {
                                return User::query()
                                    ->where(DB::raw('concat(first_name, " ", last_name)'), 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->selectRaw('*, concat(first_name, " ", last_name) as name')
                                    ->limit(50)
                                    ->pluck('name', 'id');
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->name),
                        Forms\Components\Select::make('type_id')
                            ->label(trans('admin.fields.pin_type'))
                            ->relationship('type', 'name'),
                    ]),
                Forms\Components\Fieldset::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(trans('admin.fields.title'))
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label(trans('admin.fields.description')),
                    ]),
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->label(trans('admin.fields.latitude'))
                            ->required(),
                        Forms\Components\TextInput::make('longitude')
                            ->label(trans('admin.fields.longitude'))
                            ->required(),
                    ]),
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label(trans('admin.fields.address')),
                        Forms\Components\Textarea::make('contact')
                            ->label(trans('admin.fields.contact')),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('admin.fields.name'))
                    ->formatStateUsing(fn ($state) => Str::limit($state, 30))
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(query: function (Builder $query, string $search) {
                        return $query->whereHas('user', function (Builder $query) use ($search) {
                            $query
                                ->where(DB::raw('concat(first_name, " ", last_name)'), 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('type.name')
                    ->label(trans('admin.fields.pin_type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('coordinates')
                    ->label(trans('admin.fields.coordinates'))
                    ->getStateUsing(fn (Pin $record) => "$record->latitude@$record->longitude"),
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
                                fn (Builder $query): Builder => $query->radius(
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
