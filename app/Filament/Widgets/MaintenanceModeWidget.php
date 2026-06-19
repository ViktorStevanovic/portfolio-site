<?php

namespace App\Filament\Widgets;

use App\Models\Profile;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class MaintenanceModeWidget extends Widget implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.widgets.maintenance-mode-widget';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'is_maintenance' => (bool) Profile::first()?->is_maintenance,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('is_maintenance')
                    ->label('Maintenance Mode')
                    ->helperText('When enabled, the public site shows a maintenance page.')
                    ->live()
                    ->afterStateUpdated(function (bool $state): void {
                        Profile::first()?->update(['is_maintenance' => $state]);

                        Notification::make()
                            ->title($state ? 'Maintenance mode enabled' : 'Maintenance mode disabled')
                            ->success()
                            ->send();
                    }),
            ])
            ->statePath('data');
    }
}
