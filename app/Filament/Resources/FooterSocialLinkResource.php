<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterSocialLinkResource\Pages;
use App\Models\FooterSocialLink;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FooterSocialLinkResource extends Resource
{
    protected static ?string $model = FooterSocialLink::class;
    protected static ?string $navigationIcon = 'heroicon-o-share';
    protected static ?string $navigationLabel = 'Social Links';
    protected static ?string $modelLabel = 'Social Link';
    protected static ?string $pluralModelLabel = 'Social Links';
    protected static ?string $navigationGroup = 'Footer';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Social Media Link')->schema([
                TextInput::make('platform')
                    ->label('Platform')
                    ->required()
                    ->placeholder('Facebook, Instagram, LinkedIn...'),

                TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->url()
                    ->placeholder('https://...'),

                TextInput::make('icon')
                    ->label('Icon Name')
                    ->nullable()
                    ->placeholder('heroicon-o-globe-alt'),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('platform')
                ->label('Platform')
                ->searchable()
                ->sortable(),

            TextColumn::make('url')
                ->label('URL')
                ->limit(50),

            TextColumn::make('sort_order')
                ->label('Order')
                ->sortable(),
        ])
        ->defaultSort('sort_order')
        ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFooterSocialLinks::route('/'),
            'create' => Pages\CreateFooterSocialLink::route('/create'),
            'edit'   => Pages\EditFooterSocialLink::route('/{record}/edit'),
        ];
    }
}