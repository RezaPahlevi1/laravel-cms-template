<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Posts';
    protected static ?string $modelLabel = 'Post';
    protected static ?string $pluralModelLabel = 'Posts';
    protected static ?string $navigationGroup = 'Blog';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Post Information')->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $record) {
                        if (!$record) {
                            $set('slug', str($state)->slug());
                        }
                    })
                    ->columnSpanFull(),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),

                Select::make('tags')
                    ->label('Tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ])->columns(2),

            Section::make('Thumbnail')->schema([
                FileUpload::make('thumbnail_path')
                    ->label('Thumbnail Image')
                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('post-thumbnails')
                    ->nullable()
                    ->columnSpanFull(),
            ]),

            Section::make('Excerpt')->schema([
                Textarea::make('excerpt')
                    ->label('Excerpt')
                    ->rows(3)
                    ->nullable()
                    ->helperText('Short summary shown in blog listing. If empty, will be auto-generated from content.')
                    ->columnSpanFull(),
            ]),

            Section::make('SEO')->schema([
                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->nullable()
                    ->maxLength(255)
                    ->helperText('Leave empty to use the Title above.'),

                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(2)
                    ->nullable()
                    ->maxLength(160)
                    ->helperText('Leave empty to use Excerpt, then auto-generated from content.'),
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

            Section::make('Publishing')->schema([
                Toggle::make('is_published')
                    ->label('Published')
                    ->default(false)
                    ->live(),

                DateTimePicker::make('published_at')
                    ->label('Publish Date')
                    ->nullable()
                    ->visible(fn ($get) => $get('is_published')),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('thumbnail_path')
                ->label('Thumbnail')
                ->disk('public')
                ->height(50),

            TextColumn::make('title')
                ->label('Title')
                ->searchable()
                ->sortable()
                ->limit(40),

            TextColumn::make('category.name')
                ->label('Category')
                ->placeholder('(none)')
                ->badge()
                ->sortable(),

            TextColumn::make('tags.name')
                ->label('Tags')
                ->badge()
                ->separator(','),

            IconColumn::make('is_published')
                ->label('Published')
                ->boolean(),

            TextColumn::make('published_at')
                ->label('Publish Date')
                ->dateTime('d M Y')
                ->placeholder('(draft)')
                ->sortable(),

            TextColumn::make('updated_at')
                ->label('Last Updated')
                ->dateTime('d M Y')
                ->sortable(),
        ])
        ->filters([
            TernaryFilter::make('is_published')
                ->label('Published'),

            SelectFilter::make('category')
                ->relationship('category', 'name')
                ->label('Category'),
        ])
        ->actions([
            EditAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ])
        ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}