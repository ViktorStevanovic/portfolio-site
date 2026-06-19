<?php

namespace App\Filament\Widgets;

use App\Models\Profile;
use App\Models\VisitLog;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $cvDownloads = Profile::first()?->cv_downloads ?? 0;
        $todayVisits = VisitLog::whereDate('created_at', today())->count();

        return [
            Stat::make('CV Downloads', $cvDownloads)
                ->icon('heroicon-o-arrow-down-tray'),
            Stat::make('Visits Today', $todayVisits)
                ->icon('heroicon-o-eye'),
        ];
    }
}
