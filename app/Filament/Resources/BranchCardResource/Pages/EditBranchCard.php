<?php

namespace App\Filament\Resources\BranchCardResource\Pages;

use App\Filament\Resources\BranchCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBranchCard extends EditRecord
{
    protected static string $resource = BranchCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
