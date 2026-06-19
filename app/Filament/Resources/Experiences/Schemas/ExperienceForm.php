<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role & Company')
                    ->columns(2)
                    ->schema([
                        TextInput::make('role')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('company_id')
                            ->label('Company')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('type')
                            ->options([
                                'full_time' => 'Full Time',
                                'freelance' => 'Freelance',
                                'contract' => 'Contract',
                                'internship' => 'Internship',
                            ])
                            ->native(false),
                        TextInput::make('order')
                            ->label('Display Order')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),

                Section::make('Description')
                    ->schema([
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Timeline')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('start_date')
                            ->required()
                            ->native(false),
                        DatePicker::make('end_date')
                            ->native(false)
                            ->helperText('Leave empty if currently employed'),
                    ]),

                Section::make('Technologies & Visibility')
                    ->columns(2)
                    ->schema([
                        Select::make('technologies')
                            ->relationship('technologies', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                        Toggle::make('is_visible')
                            ->label('Visible on portfolio')
                            ->inline(false)
                            ->default(true),
                    ]),
            ]);
    }
}
