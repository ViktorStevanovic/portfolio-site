<?php

namespace App\Filament\Resources\Profiles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('job_title')
                    ->required(),
                TextInput::make('tagline'),
                Textarea::make('bio')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('photo'),
                TextInput::make('cv_path'),
                TextInput::make('cv_downloads')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('github_url')
                    ->url()
                    ->required(),
                TextInput::make('linkedin_url')
                    ->url()
                    ->required(),
                TextInput::make('email_public')
                    ->email()
                    ->required(),
            ]);
    }
}
