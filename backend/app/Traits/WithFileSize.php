<?php

namespace App\Traits;

trait WithFileSize
{
    protected function formatSize($bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' ГБ';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' МБ';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' КБ';
        }
        return $bytes . ' Б';
    }
}
