<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $modelLabel = 'Setting';
    protected static ?string $pluralModelLabel = 'Site Settings';
    protected static ?int $navigationSort = 99;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('key')
                ->label('Key')
                ->required()
                ->unique(ignoreRecord: true)
                ->disabled(fn ($record) => $record !== null),

            Textarea::make('value')
                ->label('Value')
                ->rows(3)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('key')
                ->label('Key')
                ->searchable()
                ->sortable()
                ->fontFamily('mono'),

            TextColumn::make('value')
                ->label('Value')
                ->limit(60)
                ->placeholder('(empty)'),

            TextColumn::make('updated_at')
                ->label('Last Updated')
                ->dateTime('d M Y, H:i')
                ->sortable(),
        ])
        ->defaultSort('key');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit'   => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }

    protected static bool $shouldRegisterNavigation = false;
}