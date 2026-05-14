<?php

namespace App\Filament\Resources\Materials\Pages;

use App\Filament\Resources\Materials\MaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditMaterial extends EditRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('save_and_close')
                ->label('Сохранить и выйти')
                ->color('success')
                ->action(function () {
                    $this->save();
                    return redirect()->to(MaterialResource::getUrl('index'));
                }),
        ];
    }
}
