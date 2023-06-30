<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static function getNavigationGroup(): ?string
    {
        return trans('admin.nav_groups.users');
    }

    protected static function getNavigationLabel(): string
    {
        return trans('admin.nav.users');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('admin.nav.users');
    }

    public static function getLabel(): ?string
    {
        return null;
    }

    public static function getBreadcrumb(): string
    {
        return trans('admin.nav.users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label(trans('admin.fields.first_name'))
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('last_name')
                    ->label(trans('admin.fields.last_name'))
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(100),
                Forms\Components\TextInput::make('phone')
                    ->label(trans('admin.fields.phone'))
                    ->tel(),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label(trans('admin.fields.email_verified_at')),
                Forms\Components\Select::make('role')
                    ->label(trans('admin.fields.role'))
                    ->required()
                    ->disablePlaceholderSelection()
                    ->options(trans('admin.roles')),
                Forms\Components\Select::make('provider')
                    ->label(trans('admin.fields.provider'))
                    ->reactive()
                    ->options(
                        collect(config('services.auth_providers'))
                            ->mapWithKeys(fn(string $name) => [$name => ucfirst($name)])
                    ),
                Forms\Components\TextInput::make('provider_id')
                    ->label(trans('admin.fields.provider_id'))
                    ->hidden(fn(callable $get) => !$get('provider')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label(trans('admin.fields.first_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label(trans('admin.fields.last_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label(trans('admin.fields.email_verified_at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('role')
                    ->label(trans('admin.fields.role'))
                    ->enum(trans('admin.roles')),
                Tables\Columns\TextColumn::make('provider')
                    ->label(trans('admin.fields.provider')),
                Tables\Columns\TextColumn::make('provider_id')
                    ->label(trans('admin.fields.provider_id')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(trans('admin.fields.created_at'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(trans('admin.fields.created_from')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(trans('admin.fields.created_until'))
                            ->maxDate(today()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
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
                Tables\Filters\TernaryFilter::make(trans('admin.fields.verified'))
                    ->nullable()
                    ->placeholder(trans('admin.placeholders.any_type'))
                    ->attribute('email_verified_at'),
                Tables\Filters\SelectFilter::make(trans('admin.fields.provider'))
                    ->options(
                        collect(config('services.auth_providers'))
                            ->prepend('email')
                            ->mapWithKeys(fn(string $name) => [$name => ucfirst($name)])
                    )
                    ->attribute('provider')
                    ->query(function (Builder $query, array $data) {
                        $query
                            ->when(
                                $data['value'] === 'email',
                                fn(Builder $query): Builder => $query->whereNull('provider')
                            )
                            ->when(
                                $data['value'] && $data['value'] !== 'email',
                                fn(Builder $query): Builder => $query->where('provider', $data['value'])
                            );
                    })
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
            RelationManagers\AnimalsRelationManager::class,
            RelationManagers\PinsRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
