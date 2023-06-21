<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Models\Event;
use App\Models\Note;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle';

    protected static function getNavigationGroup(): ?string
    {
        return trans('admin.nav_groups.settings');
    }

    protected static function getNavigationLabel(): string
    {
        return trans('admin.nav.categories');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('admin.nav.categories');
    }

    public static function getLabel(): ?string
    {
        return null;
    }

    public static function getBreadcrumb(): string
    {
        return trans('admin.nav.categories');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make(trans('admin.fields.name'))
                    ->schema(
                        collect(config('app.available_locales'))
                            ->map(fn (string $locale) => Forms\Components\TextInput::make('name.'.$locale)->required())
                            ->toArray()
                    ),
                Forms\Components\Select::make('parent_id')
                    ->placeholder(trans('admin.tips.parent'))
                    ->reactive()
                    ->relationship('parent', 'name'),
                Forms\Components\Select::make('related_model')
                    ->placeholder(trans('admin.tips.category'))
                    ->options([
                        Event::class => trans('admin.fields.event'),
                        Note::class => trans('admin.fields.note'),
                    ])
                    ->when(fn (Closure $get) => $get('parent_id') === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        $models = [
            Event::class => trans('admin.fields.event'),
            Note::class => trans('admin.fields.note'),
        ];

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('parent.name'),
                Tables\Columns\TextColumn::make('related_model')
                    ->enum($models),
            ])
            ->filters([
                Tables\Filters\Filter::make('model')
                    ->form([
                        Forms\Components\Select::make('related_model')
                            ->reactive()
                            ->label(trans('admin.fields.model'))
                            ->options($models)
                            ->afterStateUpdated(function (callable $set) {
                                $set('parent_id', null);
                            }),
                        Forms\Components\Select::make('parent_id')
                            ->multiple()
                            ->label(trans('admin.fields.parent'))
                            ->options(function (callable $get, callable $set) {
                                if ($model = $get('related_model')) {
                                    return Category::onlyParents($model)->pluck('name', 'id');
                                }

                                $set('related_model', null);

                                return Category::whereNull('parent_id')->pluck('name', 'id');
                            }),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($model = $data['related_model']) {
                            $query->where('related_model', $model);
                        }

                        if ($parent = $data['parent_id']) {
                            $query->whereIn('parent_id', $parent);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
