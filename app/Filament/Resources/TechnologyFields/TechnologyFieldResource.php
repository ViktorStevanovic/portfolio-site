<?php

namespace App\Filament\Resources\TechnologyFields;

use App\Filament\Resources\TechnologyFields\Pages\CreateTechnologyField;
use App\Filament\Resources\TechnologyFields\Pages\EditTechnologyField;
use App\Filament\Resources\TechnologyFields\Pages\ListTechnologyFields;
use App\Filament\Resources\TechnologyFields\Schemas\TechnologyFieldForm;
use App\Filament\Resources\TechnologyFields\Tables\TechnologyFieldsTable;
use App\Models\TechnologyField;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TechnologyFieldResource extends Resource
{
    protected static ?string $model = TechnologyField::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Configurations';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TechnologyFieldForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TechnologyFieldsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTechnologyFields::route('/'),
            'create' => CreateTechnologyField::route('/create'),
            'edit' => EditTechnologyField::route('/{record}/edit'),
        ];
    }
}
