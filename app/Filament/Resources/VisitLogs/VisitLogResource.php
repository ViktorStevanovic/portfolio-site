<?php

namespace App\Filament\Resources\VisitLogs;

use App\Filament\Resources\VisitLogs\Pages\ListVisitLogs;
use App\Filament\Resources\VisitLogs\Tables\VisitLogsTable;
use App\Models\VisitLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VisitLogResource extends Resource
{
    protected static ?string $model = VisitLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Visit Logs';

    public static function table(Table $table): Table
    {
        return VisitLogsTable::configure($table);
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
            'index' => ListVisitLogs::route('/'),
        ];
    }
}
