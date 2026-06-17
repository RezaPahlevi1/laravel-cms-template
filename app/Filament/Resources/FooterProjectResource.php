<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterProjectResource\Pages;
use App\Models\FooterProject;
use App\Services\YoutubeService;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FooterProjectResource extends Resource
{
    protected static ?string $model = FooterProject::class;
    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationLabel = 'Recent Projects';
    protected static ?string $modelLabel = 'Project';
    protected static ?string $pluralModelLabel = 'Recent Projects';
    protected static ?string $navigationGroup = 'Footer';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('YouTube Video')->schema([
                TextInput::make('youtube_url')
                    ->label('YouTube URL')
                    ->required()
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set) {
                        $service = app(YoutubeService::class);
                        $id = $service->extractId($state ?? '');
                        if ($id) {
                            $set('youtube_id', $id);
                        }
                    })
                    ->columnSpanFull(),

                TextInput::make('youtube_id')
                    ->label('Video ID (auto-filled)')
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                TextInput::make('title')
                    ->label('Title')
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
            ImageColumn::make('thumbnail_url')
                ->label('Thumbnail')
                ->height(60)
                ->width(100),

            TextColumn::make('title')
                ->label('Title')
                ->placeholder('(no title)')
                ->searchable(),

            TextColumn::make('youtube_id')
                ->label('Video ID')
                ->fontFamily('mono'),

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
            'index'  => Pages\ListFooterProjects::route('/'),
            'create' => Pages\CreateFooterProject::route('/create'),
            'edit'   => Pages\EditFooterProject::route('/{record}/edit'),
        ];
    }
}