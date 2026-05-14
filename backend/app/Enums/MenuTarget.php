<?php

namespace App\Enums;

enum MenuTarget: string
{
    case SELF = '_self';
    case BLANK = '_blank';

    public function label(): string
    {
        return match($this) {
            self::SELF => 'Текущее окно',
            self::BLANK => 'Новое окно',
        };
    }
}
