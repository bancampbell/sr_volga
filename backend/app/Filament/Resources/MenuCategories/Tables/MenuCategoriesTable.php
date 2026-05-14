<?php

namespace App\Filament\Resources\MenuCategories\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class MenuCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('handle')
                    ->label('Идентификатор')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Описание')
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Активно')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort')
                    ->label('Сортировка')
                    ->sortable(),

                TextColumn::make('menus_count')
                    ->label('Пунктов меню')
                    ->counts('menus')
                    ->sortable(),
            ])
            ->defaultSort('sort')
            ->reorderable('sort');
    }
}
