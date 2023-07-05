<?php

namespace App\Filament\Resources\NoteResource\RelationManagers;

use App\Models\Category;
use App\Models\Note;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'categories';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('parent.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->onlyChildren(Note::class))
                    ->form(function (Tables\Actions\AttachAction $action) {
                        return [
                            Select::make('parent')
                                ->label(trans('admin.fields.parent'))
                                ->reactive()
                                ->options(
                                    Category::onlyParents(Note::class)
                                        ->pluck('name', 'id')
                                ),
                            $action->getRecordSelect()
                                ->options(function (callable $get) {
                                    if (!$get('parent')) {
                                        return [];
                                    }

                                    return Category::whereParentId($get('parent'))
                                        ->pluck('name', 'id');
                                })
                        ];
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
