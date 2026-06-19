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
                Section::make('Sender')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('subject')
                            ->placeholder('No subject'),
                    ]),

                Section::make('Message')
                    ->schema([
                        TextEntry::make('message')
                            ->columnSpanFull()
                            ->prose(),
                    ]),

                Section::make('Meta')
                    ->columns(2)
                    ->schema([
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
