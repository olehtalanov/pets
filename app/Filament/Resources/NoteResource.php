<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteResource\Pages;
use App\Filament\Resources\NoteResource\RelationManagers;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Note;
use App\Models\User;
use DB;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static function getNavigationGroup(): ?string
    {
        return trans('admin.nav_groups.users');
    }

    protected static function getNavigationLabel(): string
    {
        return trans('admin.nav.notes');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('admin.nav.notes');
    }

    public static function getLabel(): ?string
    {
        return strtolower(trans('admin.fields.note'));
    }

    public static function getBreadcrumb(): string
    {
        return trans('admin.nav.notes');
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
                Tables\Columns\TextColumn::make('animal.name')
                    ->label(trans('admin.fields.animal')),
                Tables\Columns\TextColumn::make('title')
                    ->label(trans('admin.fields.title'))
                    ->formatStateUsing(fn ($state) => Str::limit($state, 30)),
            ])
            ->filters([
                Tables\Filters\Filter::make('categories')
                    ->form([
                        Forms\Components\Select::make('id')
                            ->label(trans('admin.fields.category'))
                            ->multiple()
                            ->options(
                                Category::whereRelatedModel(Note::class)->pluck('name', 'id')
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
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }
}
