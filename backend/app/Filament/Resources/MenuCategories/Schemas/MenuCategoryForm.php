<?php

namespace App\Filament\Resources\MenuCategories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class MenuCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название категории')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('handle')
                    ->label('Идентификатор (main, footer, sidebar)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Уникальный идентификатор для вывода меню в шаблоне'),

                TextInput::make('description')
                    ->label('Описание')
                    ->maxLength(255)
                    ->nullable(),

                TextInput::make('sort')
                    ->label('Порядок сортировки')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label('Активно')
                    ->default(true),
            ]);
    }
}
