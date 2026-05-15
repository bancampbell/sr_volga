<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use App\Enums\MenuTarget;
use App\Models\Material;
use App\Models\Category;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Меню')
                    ->tabs([
                        Tab::make('Основное')
                            ->schema([
                                Select::make('menu_category_id')
                                    ->label('Категория меню')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Без категории'),

                                Select::make('parent_id')
                                    ->label('Родительский пункт')
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('name')
                                    ->label('Название')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('handle', \Illuminate\Support\Str::slug($state));
                                    }),

                                TextInput::make('handle')
                                    ->label('Идентификатор меню')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),

                        Tab::make('Тип ссылки')
                            ->schema([
                                Select::make('type')
                                    ->label('Что будет открывать пункт меню?')
                                    ->options([
                                        'link' => 'Обычная ссылка (URL)',
                                        'divider' => 'Заглушка (разделитель)',
                                        'material' => 'Материал',
                                        'category' => 'Категория',
                                    ])
                                    ->required()
                                    ->live(),

                                TextInput::make('url')
                                    ->label('URL адрес')
                                    ->maxLength(255)
                                    ->dehydrated(true),

                                Select::make('material_id')
                                    ->label('Выберите материал')
                                    ->options(Material::pluck('title', 'id'))
                                    ->visible(fn ($get) => $get('type') === 'material')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(fn ($state, callable $set) =>
                                    $set('url', Material::find($state)?->getUrl())
                                    ),

                                Select::make('category_id')
                                    ->label('Выберите категорию')
                                    ->options(Category::pluck('name', 'id'))
                                    ->visible(fn ($get) => $get('type') === 'category')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(fn ($state, callable $set) =>
                                    $set('url', Category::find($state)?->getUrl())
                                    ),

                                Select::make('target')
                                    ->label('Открывать в')
                                    ->options(MenuTarget::class)
                                    ->default(MenuTarget::SELF->value),
                            ]),

                        Tab::make('Оформление')
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Иконка')
                                    ->placeholder('heroicon-o-home')
                                    ->maxLength(255),

                                Toggle::make('is_active')
                                    ->label('Активно')
                                    ->default(true),

                                Toggle::make('is_new_tab')
                                    ->label('Открывать в новой вкладке')
                                    ->default(false),

                                TextInput::make('sort')
                                    ->label('Порядок сортировки')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),
            ]);
    }
}
