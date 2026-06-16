<?php

namespace App\Filament\Resources\TechnologyFields\Pages;

use App\Filament\Resources\TechnologyFields\TechnologyFieldResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTechnologyField extends EditRecord
{
    protected static string $resource = TechnologyFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
