<?php

namespace App\Filament\Resources\Profiles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProfileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('job_title'),
                TextEntry::make('tagline')
                    ->placeholder('-'),
                TextEntry::make('bio')
                    ->columnSpanFull(),
                TextEntry::make('photo')
                    ->placeholder('-'),
                TextEntry::make('cv_path')
                    ->placeholder('-'),
                TextEntry::make('cv_downloads')
                    ->numeric(),
                TextEntry::make('github_url'),
                TextEntry::make('linkedin_url'),
                TextEntry::make('email_public'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
