<?php

namespace App\Filament\Resources;

use App\Enums\EventRepeatSchemeEnum;
use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use DB;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static function getNavigationGroup(): ?string
    {
        return trans('admin.nav_groups.users');
    }

    protected static function getNavigationLabel(): string
    {
        return trans('admin.nav.events');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('admin.nav.events');
    }

    public static function getLabel(): ?string
    {
        return strtolower(trans('admin.fields.event'));
    }

    public static function getBreadcrumb(): string
    {
        return trans('admin.nav.events');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label(trans('admin.fields.user'))
                            ->relationship('user', 'id')
                            ->getSearchResultsUsing(function (string $search) {
                                return User::query()
                                    ->where(DB::raw('concat(first_name, " ", last_name)'), 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->selectRaw('*, concat(first_name, " ", last_name) as name')
                                    ->limit(50)
                                    ->pluck('name', 'id');
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->name)
                            ->reactive()
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('animal_id')
                            ->label(trans('admin.fields.animal'))
                            ->disabled(fn (callable $get) => !$get('user_id'))
                            ->relationship('animal', 'name')
                            ->options(fn (callable $get) => Animal::where(
                                'user_id',
                                $get('user_id')
                            )->pluck('name', 'id'))
                            ->reactive()
                            ->required(),
                    ]),
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(trans('admin.fields.title'))
                            ->required()
                            ->maxLength(191)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label(trans('admin.fields.description'))
                            ->columnSpanFull()
                            ->maxLength(65535),
                    ]),
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label(trans('admin.fields.starts_at')),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label(trans('admin.fields.ends_at')),
                        Forms\Components\Select::make('repeat_scheme')
                            ->label(trans('admin.fields.repeat_scheme'))
                            ->options(EventRepeatSchemeEnum::getNames())
                            ->required(),
                        Forms\Components\Select::make('original_id')
                            ->label(trans('admin.fields.original_event'))
                            ->disabled(fn (callable $get) => !$get('animal_id'))
                            ->options(fn (callable $get, ?Model $record) => Event::query()
                                ->where('animal_id', $get('animal_id'))
                                ->where('id', '!=', $record?->id)
                                ->pluck('title', 'id')),
                        Forms\Components\Toggle::make('whole_day')
                            ->columnSpanFull()
                            ->label(trans('admin.fields.whole_day'))
                            ->required(),
                        Forms\Components\Toggle::make('processable')
                            ->default(true)
                            ->hidden(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(trans('admin.fields.user'))
                    ->searchable(query: function (Builder $query, string $search) {
                        return $query->whereHas('user', function (Builder $query) use ($search) {
                            $query
                                ->where(DB::raw('concat(first_name, " ", last_name)'), 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->label(trans('admin.fields.title'))
                    ->formatStateUsing(fn ($state) => Str::limit($state, 30)),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label(trans('admin.fields.starts_at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label(trans('admin.fields.ends_at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('repeat_scheme')
                    ->label(trans('admin.fields.repeat_scheme')),
                Tables\Columns\IconColumn::make('whole_day')
                    ->label(trans('admin.fields.whole_day'))
                    ->alignCenter()
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\Filter::make('categories')
                    ->form([
                        Forms\Components\Select::make('id')
                            ->label(trans('admin.fields.category'))
                            ->multiple()
                            ->options(
                                Category::whereRelatedModel(Event::class)->pluck('name', 'id')
                            )
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['id'],
                                fn (Builder $query): Builder => $query->whereHas(
                                    'categories',
                                    fn (Builder $builder) => $builder
                                    ->whereIn('id', $data['id'])
                                    ->orWhereIn('parent_id', $data['id'])
                                ),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
