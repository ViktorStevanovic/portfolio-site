<?php

namespace App\Filament\Resources\Technologies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TechnologyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('code')
                            ->required(),
                        Select::make('technology_field_id')
                            ->label('Field / Category')
                            ->relationship('technologyField', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('order')
                            ->label('Display Order')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('icon')
                            ->helperText('Icon class name (e.g. devicon:laravel)')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
