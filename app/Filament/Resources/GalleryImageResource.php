<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryImageResource\Pages;
use App\Models\GalleryImage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalleryImageResource extends Resource
{
    protected static ?string $model = GalleryImage::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Gallery';
    protected static ?string $modelLabel = 'Gallery Image';
    protected static ?string $pluralModelLabel = 'Gallery Images';
    protected static ?string $navigationGroup = 'Home Page';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Image')->schema([
                FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('gallery')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('caption')
                    ->label('Caption')
                    ->nullable(),

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
            ImageColumn::make('image_path')
                ->label('Image')
                ->disk('public')
                ->height(60),

            TextColumn::make('caption')
                ->label('Caption')
                ->placeholder('(no caption)')
                ->searchable(),

            TextColumn::make('sort_order')
                ->label('Order')
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Uploaded')
                ->dateTime('d M Y')
                ->sortable(),
        ])
        ->defaultSort('sort_order')
        ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGalleryImages::route('/'),
            'create' => Pages\CreateGalleryImage::route('/create'),
            'edit'   => Pages\EditGalleryImage::route('/{record}/edit'),
        ];
    }
}