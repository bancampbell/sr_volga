<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label('URL')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('parent_id')
                    ->label('Родительская категория')
                    ->options(function ($livewire) {
                        $query = \App\Models\Category::query();

                        // При редактировании исключаем текущую категорию и её потомков
                        if ($livewire->record) {
                            $categoryIds = [$livewire->record->id];
                            $childrenIds = $livewire->record->children->pluck('id')->toArray();
                            $excludeIds = array_merge($categoryIds, $childrenIds);
                            $query->whereNotIn('id', $excludeIds);
                        }

                        return $query->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->placeholder('-- Без родителя --'),
            ]);
    }
}
