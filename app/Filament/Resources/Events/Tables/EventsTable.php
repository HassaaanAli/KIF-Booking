<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->halls->pluck('name')->join(', ') ?: 'No halls assigned'),

                TextColumn::make('halls.name')
                    ->label('Halls')
                    ->badge()
                    ->separator(',')
                    ->searchable(),

                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'active',
                        'gray' => 'completed',
                    ])
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->label('Start Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->description(fn ($record) =>
                        $record->starts_at > now()
                            ? 'Starts ' . $record->starts_at->diffForHumans()
                            : ($record->ends_at < now() ? 'Ended' : 'In Progress')
                    ),

                TextColumn::make('ends_at')
                    ->label('End Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'completed' => 'Completed',
                    ])
                    ->multiple(),

                SelectFilter::make('hall')
                    ->relationship('halls', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('starts_at', 'desc');
    }
}
