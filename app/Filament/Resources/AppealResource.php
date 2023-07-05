<?php

namespace App\Filament\Resources;

use App\Enums\AppealStatusEnum;
use App\Filament\Resources\AppealResource\Pages;
use App\Models\Appeal;
use App\Models\User;
use DB;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class AppealResource extends Resource
{
    protected static ?string $model = Appeal::class;

    protected static ?string $navigationIcon = 'heroicon-o-support';

    public static function canCreate(): bool
    {
        return false;
    }

    protected static function getNavigationLabel(): string
    {
        return trans('admin.nav.appeals');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('admin.nav.appeals');
    }

    public static function getLabel(): ?string
    {
        return strtolower(trans('admin.fields.appeal'));
    }

    public static function getBreadcrumb(): string
    {
        return trans('admin.nav.appeals');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->columnSpan(2)
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
                    ->getOptionLabelUsing(fn($value): ?string => User::find($value)?->name)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label(trans('admin.fields.status'))
                    ->disablePlaceholderSelection()
                    ->options(
                        collect(AppealStatusEnum::cases())
                            ->mapWithKeys(fn(AppealStatusEnum $enum) => [$enum->value => $enum->getName()])
                    ),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Radio::make('rating')
                    ->label(trans('admin.fields.rating'))
                    ->columnSpanFull()
                    ->required()
                    ->inline()
                    ->options(trans('admin.rating_statuses')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(trans('admin.fields.name')),
                Tables\Columns\TextColumn::make('rating')
                    ->label(trans('admin.fields.rating'))
                    ->alignCenter(),
                Tables\Columns\SelectColumn::make('status')
                    ->label(trans('admin.fields.status'))
                    ->disablePlaceholderSelection()
                    ->options(
                        collect(AppealStatusEnum::cases())
                            ->mapWithKeys(fn(AppealStatusEnum $enum) => [$enum->value => $enum->getName()])
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(trans('admin.fields.created_at'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAppeals::route('/'),
            'create' => Pages\CreateAppeal::route('/create'),
            'edit' => Pages\EditAppeal::route('/{record}/edit'),
        ];
    }
}
