<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class GeneralSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'General Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int    $navigationSort  = 10;
    protected static string  $view            = 'filament.pages.general-settings-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name'            => SiteSetting::get('site_name', ''),
            'site_tagline'         => SiteSetting::get('site_tagline', ''),
            'meta_description'     => SiteSetting::get('meta_description', ''),
            'logo_mode'            => SiteSetting::get('logo_mode', 'text'),
            'logo_path'            => SiteSetting::get('logo_path', '') ?: null,
            'what_we_do_heading'   => SiteSetting::get('what_we_do_heading', ''),
            'what_we_do_body'      => SiteSetting::get('what_we_do_body', ''),
            'gallery_per_page'     => SiteSetting::get('gallery_per_page', '12'),
            'footer_about_text'    => SiteSetting::get('footer_about_text', ''),
            'footer_contact_address' => SiteSetting::get('footer_contact_address', ''),
            'footer_contact_phone'   => SiteSetting::get('footer_contact_phone', ''),
            'footer_contact_fax'     => SiteSetting::get('footer_contact_fax', ''),
            'footer_contact_email'   => SiteSetting::get('footer_contact_email', ''),
            'footer_projects_title'  => SiteSetting::get('footer_projects_title', 'Recent Projects'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Site Identity')->schema([
                    TextInput::make('site_name')
                        ->label('Site Name')
                        ->required(),

                    TextInput::make('site_tagline')
                        ->label('Tagline')
                        ->nullable(),

                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->helperText('Shown in search engine results. Recommended 150–160 characters.')
                        ->rows(2)
                        ->nullable(),
                ]),

                Section::make('Logo')->schema([
                    Select::make('logo_mode')
                        ->label('Logo Display Mode')
                        ->options([
                            'text'  => 'Text — show site name as text',
                            'image' => 'Image — show uploaded logo image',
                        ])
                        ->default('text')
                        ->required()
                        ->live()
                        ->helperText('Switch between text logo and image logo.'),

                    FileUpload::make('logo_path')
                        ->label('Logo Image')
                        ->image()
                        ->directory('logos')
                        ->nullable()
                        ->visible(fn ($get) => $get('logo_mode') === 'image')
                        ->helperText('Recommended: PNG with transparent background, height 40–60px.'),
                ])->columns(1),

                Section::make('Home Page — What We Do')->schema([
                    TextInput::make('what_we_do_heading')
                        ->label('Heading')
                        ->nullable(),

                    Textarea::make('what_we_do_body')
                        ->label('Body Text')
                        ->rows(5)
                        ->nullable()
                        ->helperText('Supports basic HTML.'),
                ]),

                Section::make('Gallery')->schema([
                    TextInput::make('gallery_per_page')
                        ->label('Images per Page')
                        ->numeric()
                        ->minValue(4)
                        ->maxValue(48)
                        ->default(12),
                ]),

                Section::make('Footer')->schema([
                    Textarea::make('footer_about_text')
                        ->label('About Text')
                        ->rows(3)
                        ->nullable(),

                    TextInput::make('footer_contact_address')
                        ->label('Address')
                        ->nullable(),

                    TextInput::make('footer_contact_phone')
                        ->label('Phone')
                        ->nullable(),

                    TextInput::make('footer_contact_fax')
                        ->label('Fax')
                        ->nullable(),

                    TextInput::make('footer_contact_email')
                        ->label('Email')
                        ->email()
                        ->nullable(),

                    TextInput::make('footer_projects_title')
                        ->label('Videos Section Title')
                        ->helperText('Heading for the video section in footer.')
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            // FileUpload mengembalikan array saat ada file baru
            if (is_array($value)) {
                $value = $value[0] ?? '';
            }
            SiteSetting::set($key, $value ?? '');
        }

        // Clear semua cache settings
        Cache::forget('site_setting_site_name');
        Cache::forget('site_setting_logo_path');
        Cache::forget('site_setting_logo_mode');
        Cache::forget('site_setting_footer_projects_title');
        Cache::forget('nav_tree');

        Notification::make()
            ->title('Settings saved successfully.')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [];
    }
}