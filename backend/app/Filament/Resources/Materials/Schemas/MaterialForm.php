<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($operation, $state, callable $set) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->label('URL')
                    ->required()
                    ->unique(ignoreRecord: true),
                RichEditor::make('content')
                    ->label('Содержимое')
                    ->columnSpanFull(),
                ViewField::make('link_modal')
                    ->label('')
                    ->view('components.livewire-wrapper'),
                Toggle::make('is_active')
                    ->label('Активно')
                    ->default(true),
            ]);
    }
}
