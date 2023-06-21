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
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(100),
                Forms\Components\TextInput::make('phone')
                    ->tel(),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\Select::make('role')
                    ->required()
                    ->disablePlaceholderSelection()
                    ->options(trans('admin.roles')),
                Forms\Components\Select::make('provider')
                    ->reactive()
                    ->options(
                        collect(config('services.auth_providers'))
                            ->mapWithKeys(fn (string $name) => [$name => ucfirst($name)])
                    ),
                Forms\Components\TextInput::make('provider_id')
                    ->hidden(fn (callable $get) => ! $get('provider')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->formatStateUsing(fn (User $record) => "$record->first_name $record->last_name"),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('email_verified_at')->dateTime(),
                Tables\Columns\TextColumn::make('role')->enum(trans('admin.roles')),
                Tables\Columns\TextColumn::make('provider'),
                Tables\Columns\TextColumn::make('provider_id'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
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
