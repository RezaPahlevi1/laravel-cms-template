<?php

namespace App\Filament\Resources\FooterSocialLinkResource\Pages;

use App\Filament\Resources\FooterSocialLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFooterSocialLinks extends ListRecords
{
    protected static string $resource = FooterSocialLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
