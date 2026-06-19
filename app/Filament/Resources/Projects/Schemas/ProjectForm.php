<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('code')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'archived' => 'Archived',
                            ])
                            ->required()
                            ->native(false),
                        TextInput::make('short_description')
                            ->columnSpanFull(),
                    ]),

                Section::make('Description')
                    ->schema([
                        Textarea::make('description')
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),

                Section::make('Technical Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('repository_url')
                            ->label('Repository URL')
                            ->url()
                            ->columnSpanFull(),
                        TextInput::make('demo_url')
                            ->label('Demo URL')
                            ->url()
                            ->columnSpanFull(),
                        Select::make('technologies')
                            ->relationship('technologies', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                        TextInput::make('order')
                            ->label('Display Order')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_visible')
                            ->label('Visible on portfolio')
                            ->inline(false)
                            ->default(true),
                        Toggle::make('is_featured')
                            ->label('Featured on homepage')
                            ->inline(false)
                            ->default(false),
                    ]),

                Section::make('Gallery')
                    ->schema([
                        Repeater::make('images')
                            ->relationship()
                            ->schema([
                                FileUpload::make('image')
                                    ->image()
                                    ->disk('public')
                                    ->directory(function (Get $get) {
                                        $code = $get('../../code');

                                        return 'projects/images/'.($code ?? 'unsorted');
                                    })
                                    ->required(),
                                TextInput::make('caption')
                                    ->maxLength(255),
                                Toggle::make('is_cover')
                                    ->label('Use as cover image')
                                    ->inline(false)
                                    ->default(false),
                            ])
                            ->columns(3)
                            ->orderColumn('order')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->defaultItems(0)
                            ->addActionLabel('Add image'),
                    ]),
            ]);
    }
}
