<?php

namespace App\Filament\Resources\TechnologyFields\Pages;

use App\Filament\Resources\TechnologyFields\TechnologyFieldResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTechnologyFields extends ListRecords
{
    protected static string $resource = TechnologyFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
