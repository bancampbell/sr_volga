<?php

namespace App\Filament\Resources\Menus\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->formatStateUsing(fn ($state, $record) => str_repeat('— ', $record->depth ?? 0) . $state)
                    ->weight(fn ($record) => $record->depth === 0 ? 'bold' : 'normal')
                    ->color(fn ($record) => $record->depth === 0 ? 'primary' : 'gray')
                    ->searchable()
                    ->sortable(),

// Убери defaultSort('sort') и добавь:

                TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->sortable(),

                TextColumn::make('parent.name')
                    ->label('Родитель')
                    ->sortable(),

                TextColumn::make('url')
                    ->label('URL')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Активно')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort')
                    ->label('Сортировка')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('_lft')
            ->reorderable('sort')
            ->groups([
                \Filament\Tables\Grouping\Group::make('category.name')
                    ->label('')
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn ($record): string => $record->category?->name ?? 'Без категории'),
            ])
            ->defaultGroup('category.name')
            ->filters([
                SelectFilter::make('menu_category_id')
                    ->label('Категория')
                    ->relationship('category', 'name'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
