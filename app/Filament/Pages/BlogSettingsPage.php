<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class BlogSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Blog Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int    $navigationSort  = 11;
    protected static string  $view            = 'filament.pages.blog-settings-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'blog_enabled'  => SiteSetting::get('blog_enabled', 'true') === 'true',
            'blog_title'    => SiteSetting::get('blog_title', 'Blog'),
            'blog_per_page' => SiteSetting::get('blog_per_page', '9'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Blog Configuration')->schema([
                    Toggle::make('blog_enabled')
                        ->label('Enable Blog')
                        ->helperText('When disabled, all /blog/* routes return 404 and the blog link is hidden from navigation.')
                        ->default(true),

                    TextInput::make('blog_title')
                        ->label('Blog Title')
                        ->helperText('Shown in navigation, page header, and browser title.')
                        ->required(),

                    TextInput::make('blog_per_page')
                        ->label('Posts per Page')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(50)
                        ->default(9),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::set('blog_enabled',  $data['blog_enabled'] ? 'true' : 'false');
        SiteSetting::set('blog_title',    $data['blog_title']);
        SiteSetting::set('blog_per_page', (string) $data['blog_per_page']);

        Cache::forget('site_setting_blog_enabled');
        Cache::forget('site_setting_blog_title');
        Cache::forget('site_setting_blog_per_page');

        Notification::make()
            ->title('Blog settings saved.')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [];
    }
}