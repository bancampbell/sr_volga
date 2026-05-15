<?php

namespace App\Filament\Resources\Menus;

use App\Filament\Resources\Menus\Pages\CreateMenu;
use App\Filament\Resources\Menus\Pages\EditMenu;
use App\Filament\Resources\Menus\Pages\ListMenus;
use App\Filament\Resources\Menus\Schemas\MenuForm;
use App\Filament\Resources\Menus\Tables\MenusTable;
use App\Models\Category;
use App\Models\Material;
use App\Models\Menu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Меню';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return self::mutateFormData($data);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        return self::mutateFormData($data);
    }

    private static function mutateFormData(array $data): array
    {
        if (isset($data['material_id']) && $data['material_id']) {
            $data['linkable_id'] = $data['material_id'];
            $data['linkable_type'] = Material::class;
            $data['url'] = Material::find($data['material_id'])?->getUrl();
            unset($data['material_id'], $data['category_id']);
        }

        if (isset($data['category_id']) && $data['category_id']) {
            $data['linkable_id'] = $data['category_id'];
            $data['linkable_type'] = Category::class;
            $data['url'] = Category::find($data['category_id'])?->getUrl();
            unset($data['category_id'], $data['material_id']);
        }

        if (isset($data['url']) && !empty($data['url'])) {
            $data['linkable_id'] = null;
            $data['linkable_type'] = null;
            unset($data['material_id'], $data['category_id']);
        }

        return $data;
    }
}
