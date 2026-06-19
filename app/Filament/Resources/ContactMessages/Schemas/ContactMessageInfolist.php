<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('From'),

                        TextEntry::make('email')
                            ->label('Email'),

                        TextEntry::make('subject')
                            ->label('Subject')
                            ->placeholder('No subject')
                            ->columnSpanFull(),

                        TextEntry::make('message')
                            ->label('Message')
                            ->columnSpanFull()
                            ->prose(),

                        TextEntry::make('created_at')
                            ->label('Received at')
                            ->dateTime(),

                        TextEntry::make('read_at')
                            ->label('Read at')
                            ->dateTime()
                            ->placeholder('Unread'),
                    ]),
            ]);
    }
}
