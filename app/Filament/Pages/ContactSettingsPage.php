<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class ContactSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Contact Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int    $navigationSort  = 12;
    protected static string  $view            = 'filament.pages.contact-settings-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'contact_enabled'          => SiteSetting::get('contact_enabled', 'true') === 'true',
            'contact_form_recipient'   => SiteSetting::get('contact_form_recipient', ''),
            'map_embed_url'            => SiteSetting::get('map_embed_url', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contact Page')->schema([
                    Toggle::make('contact_enabled')
                        ->label('Enable Contact Page')
                        ->helperText('When disabled, /contact-us returns 404 and the link is hidden from navigation.')
                        ->default(true),

                    TextInput::make('contact_form_recipient')
                        ->label('Form Recipient Email')
                        ->email()
                        ->nullable()
                        ->helperText('All contact form submissions will be sent to this email address.'),
                ]),

                Section::make('Google Maps')->schema([
                    Textarea::make('map_embed_url')
                        ->label('Google Maps Embed URL')
                        ->rows(3)
                        ->nullable()
                        ->helperText(
                            'How to get the embed URL: ' .
                            '1) Open Google Maps and search your location. ' .
                            '2) Click "Share". ' .
                            '3) Choose "Embed a map". ' .
                            '4) Click "Copy HTML". ' .
                            '5) From the copied HTML, paste only the URL inside src="..." here. ' .
                            'Example: https://www.google.com/maps/embed?pb=...'
                        ),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::set('contact_enabled',        $data['contact_enabled'] ? 'true' : 'false');
        SiteSetting::set('contact_form_recipient', $data['contact_form_recipient'] ?? '');
        SiteSetting::set('map_embed_url',          $data['map_embed_url'] ?? '');

        Cache::forget('site_setting_contact_enabled');
        Cache::forget('site_setting_contact_form_recipient');
        Cache::forget('site_setting_map_embed_url');

        Notification::make()
            ->title('Contact settings saved.')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [];
    }
}