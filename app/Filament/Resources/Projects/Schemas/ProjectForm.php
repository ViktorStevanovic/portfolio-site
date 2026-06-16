<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Select::make('status')
                    ->options(['in_progress' => 'In progress', 'completed' => 'Completed', 'archived' => 'Archived'])
                    ->required(),
                TextInput::make('short_description'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('repository_url')
                    ->url(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_visible')
                    ->required(),
                // In your ProjectResource form schema


                Repeater::make('images')
                    ->relationship()
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory(function (Get $get) {
                                $code = $get('../../code'); // go up two levels: image → repeater → project form
                                return 'projects/images/' . ($code ?? 'unsorted');
                            })
                            ->required(),
                        TextInput::make('caption')
                            ->maxLength(255),
                        Toggle::make('is_cover')
                            ->label('Use as cover image')
                            ->default(false),
                    ])
                    ->orderColumn('order')
                    ->reorderable()
                    ->collapsible()
                    ->cloneable()
                    ->defaultItems(0)
                    ->addActionLabel('Add image')
            ]);
    }
}
