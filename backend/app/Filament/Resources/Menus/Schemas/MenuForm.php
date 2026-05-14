<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use App\Enums\MenuType;
use App\Enums\MenuTarget;

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
                                    ->maxLength(255),

                                TextInput::make('handle')
                                    ->label('Идентификатор меню (main, footer, sidebar)')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                Select::make('type')
                                    ->label('Тип ссылки')
                                    ->options(MenuType::class)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($set) => $set('url', null)),
                            ]),

                        Tab::make('Ссылка')
                            ->schema([
                                TextInput::make('url')
                                    ->label('URL')
                                    ->visible(fn ($get) => $get('type') instanceof MenuType && $get('type')->requiresUrl())
                                    ->maxLength(255),

                                TextInput::make('external_url')
                                    ->label('Внешний URL')
                                    ->visible(fn ($get) => $get('type') === MenuType::EXTERNAL)
                                    ->url()
                                    ->maxLength(255),

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
