<?php

namespace App\Filament\Resources\FooterSocialLinkResource\Pages;

use App\Filament\Resources\FooterSocialLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFooterSocialLink extends EditRecord
{
    protected static string $resource = FooterSocialLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
