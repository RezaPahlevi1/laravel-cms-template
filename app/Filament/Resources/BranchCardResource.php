<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchCardResource\Pages;
use App\Models\BranchCard;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BranchCardResource extends Resource
{
    protected static ?string $model = BranchCard::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Branch Cards';
    protected static ?string $modelLabel = 'Branch Card';
    protected static ?string $pluralModelLabel = 'Branch Cards';
    protected static ?string $navigationGroup = 'Home Page';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Card Content')->schema([
                FileUpload::make('image_path')
                    ->label('Card Image')
                    ->image()
                    ->directory('branch-cards')
                    ->nullable()
                    ->columnSpanFull(),

                TextInput::make('title')
                    ->label('Title')
                    ->required(),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->nullable(),

                TextInput::make('link_url')
                    ->label('Link URL')
                    ->nullable()
                    ->placeholder('/services or https://...'),
            ]),

            Section::make('Settings')->schema([
                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image_path')
                ->label('Image')
                ->height(60),

            TextColumn::make('title')
                ->label('Title')
                ->searchable()
                ->sortable(),

            TextColumn::make('description')
                ->label('Description')
                ->limit(50)
                ->placeholder('(none)'),

            TextColumn::make('sort_order')
                ->label('Order')
                ->sortable(),

            IconColumn::make('is_active')
                ->label('Active')
                ->boolean(),
        ])
        ->defaultSort('sort_order')
        ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBranchCards::route('/'),
            'create' => Pages\CreateBranchCard::route('/create'),
            'edit'   => Pages\EditBranchCard::route('/{record}/edit'),
        ];
    }
}