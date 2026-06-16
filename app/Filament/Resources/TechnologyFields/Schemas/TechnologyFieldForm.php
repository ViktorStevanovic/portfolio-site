<?php

namespace App\Filament\Resources\TechnologyFields\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TechnologyFieldForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
