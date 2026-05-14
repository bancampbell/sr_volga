<?php

namespace App\Actions\FileManager;

class NavigateToFileAction
{
    public function execute(string $path): array
    {
        if (!str_contains($path, '/storage/')) {
            return ['currentPath' => '', 'filename' => '', 'fullPath' => ''];
        }

        $relativePath = str_replace('/storage/', '', $path);
        $directory = dirname($relativePath);
        $filename = basename($relativePath);

        $currentPath = ($directory !== '.' && $directory !== '/') ? $directory : '';
        $fullPath = ($currentPath ? $currentPath . '/' : '') . $filename;

        return [
            'currentPath' => $currentPath,
            'filename' => $filename,
            'fullPath' => $fullPath,
        ];
    }
}
