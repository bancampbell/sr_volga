<?php

namespace App\Enums;

enum MenuType: string
{
    case LINK = 'link';
    case PAGE = 'page';
    case CATEGORY = 'category';
    case MATERIAL = 'material';
    case CUSTOM = 'custom';
    case EXTERNAL = 'external';

    public function label(): string
    {
        return match($this) {
            self::LINK => 'Обычная ссылка',
            self::PAGE => 'Страница',
            self::CATEGORY => 'Категория',
            self::MATERIAL => 'Материал',
            self::CUSTOM => 'Произвольный URL',
            self::EXTERNAL => 'Внешняя ссылка',
        };
    }

    public function requiresUrl(): bool
    {
        return in_array($this, [self::LINK, self::CUSTOM, self::EXTERNAL]);
    }

    public function requiresModel(): bool
    {
        return in_array($this, [self::PAGE, self::CATEGORY, self::MATERIAL]);
    }
}
