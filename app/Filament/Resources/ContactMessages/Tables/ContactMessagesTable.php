<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('subject')
                    ->searchable()
                    ->placeholder('No subject')
                    ->limit(50),
                TextColumn::make('read_at')
                    ->label('Status')
                    ->badge()
                    ->state(fn (ContactMessage $record): string => $record->read_at ? 'Read' : 'Unread')
                    ->color(fn (ContactMessage $record): string => $record->read_at ? 'success' : 'warning'),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('unread')
                    ->label('Unread only')
                    ->query(fn (Builder $query) => $query->whereNull('read_at')),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('markAsRead')
                    ->label('Mark as read')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->action(fn (ContactMessage $record) => $record->update(['read_at' => now()]))
                    ->visible(fn (ContactMessage $record): bool => is_null($record->read_at)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
