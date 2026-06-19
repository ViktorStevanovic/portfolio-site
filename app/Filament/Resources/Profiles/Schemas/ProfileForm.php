<?php

namespace App\Filament\Resources\Profiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile Info')
                    ->columns(2)
                    ->schema([
                        TextInput::make('job_title')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('tagline')
                            ->columnSpanFull(),
                        Textarea::make('bio')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Social & Contact')
                    ->columns(3)
                    ->schema([
                        TextInput::make('github_url')
                            ->label('GitHub URL')
                            ->url()
                            ->required(),
                        TextInput::make('linkedin_url')
                            ->label('LinkedIn URL')
                            ->url()
                            ->required(),
                        TextInput::make('email_public')
                            ->label('Public Email')
                            ->email()
                            ->required(),
                        TextInput::make('twitter_url')
                            ->label('Twitter / X URL')
                            ->url(),
                        TextInput::make('website_url')
                            ->label('Website URL')
                            ->url(),
                    ]),

                Section::make('Media')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('photo')
                            ->image()
                            ->disk('public')
                            ->directory('profile/photos'),
                        FileUpload::make('cv_path')
                            ->label('CV / Resume')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('profile/cv'),
                    ]),

            ]);
    }
}
