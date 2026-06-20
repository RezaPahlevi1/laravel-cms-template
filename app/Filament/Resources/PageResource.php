<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;


class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pages';
    protected static ?string $modelLabel = 'Page';
    protected static ?string $pluralModelLabel = 'Pages';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Page Information')->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $record) {
                        // Hanya auto-generate slug jika ini halaman baru
                        if (!$record) {
                            $set('slug', str($state)->slug());
                        }
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled(fn ($record) => $record?->slug === 'home')
                    ->helperText(fn ($record) => $record?->slug === 'home'
                        ? 'Home page slug cannot be changed. Content is managed via the Home Page menu.'
                        : 'Auto-generated from title.'
                    ),

                Select::make('parent_id')
                    ->label('Parent Page')
                    ->placeholder('— No Parent (Top Level) —')
                    ->options(function ($record) {
                        return Page::query()
                            ->where('slug', '!=', 'home')
                            ->where('depth', '<', 2)
                            ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            ->pluck('title', 'id');
                    })
                    ->searchable()
                    ->nullable(),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),
            ])->columns(2),

            Section::make('Hero Banner')->schema([
                FileUpload::make('hero_image_path')
                    ->label('Hero Image')
                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('hero-images')
                    ->nullable()
                    ->columnSpanFull(),
            ]),

            Section::make('Content')->schema([
                TiptapEditor::make('content')
                    ->label('Page Content') // 'Post Content' di PostResource
                    ->profile('default')
                    ->tools([
                        'heading',
                        'bold', 'italic', 'underline', 'strike',
                        'superscript', 'subscript',
                        'bullet-list', 'ordered-list',
                        'blockquote', 'hr',
                        'align-left', 'align-center', 'align-right',
                        'link', 'media',
                        'code', 'code-block',
                    ])
                    ->nullable()
                    ->columnSpanFull(),
            ]),

            Section::make('SEO')->schema([
                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->nullable()
                    ->maxLength(255)
                    ->helperText('Shown in browser tab & search results. Leave empty to use the Title above.'),

                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(2)
                    ->nullable()
                    ->maxLength(160)
                    ->helperText('Shown in search results & link previews. Leave empty to use the global default.'),
            ]),

            Section::make('Visibility')->schema([
                Toggle::make('is_published')
                    ->label('Published')
                    ->default(true),

                Toggle::make('show_in_nav')
                    ->label('Show in Navigation')
                    ->default(true)
                    ->disabled(fn ($record) => $record?->slug === 'home'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->where('slug', '!=', 'home'))
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        // Indentasi visual berdasarkan depth
                        $indent = str_repeat('— ', $record->depth);
                        return $indent . $state;
                    }),

                TextColumn::make('full_path')
                    ->label('URL Path')
                    ->fontFamily('mono')
                    ->searchable(),

                TextColumn::make('depth')
                    ->label('Level')
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        0 => 'Top Level',
                        1 => 'Child',
                        2 => 'Grandchild',
                        default => $state,
                    }),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                IconColumn::make('show_in_nav')
                    ->label('In Nav')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Published'),

                TernaryFilter::make('show_in_nav')
                    ->label('In Navigation'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn ($record) => $record->slug === 'home'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                if ($record->slug !== 'home') {
                                    $record->delete();
                                }
                            });
                        }),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}