<?php

namespace App\Filament\Resources\FooterProjectResource\Pages;

use App\Filament\Resources\FooterProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFooterProject extends EditRecord
{
    protected static string $resource = FooterProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
