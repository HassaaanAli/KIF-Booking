<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->description('')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Event Name')
                            ->placeholder('e.g., Tech Conference 2025')
                            ->columnSpanFull(),

                        Select::make('status')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Active',
                                'completed' => 'Completed',
                            ])
                            ->default('draft')
                            ->native(false)
                            ->helperText('Draft: Not visible. Active: Currently running. Completed: Finished.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Venue & Schedule')
                    ->description('Select halls and event timing')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('halls')
                            ->relationship('halls', 'name')
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Halls')
                            ->helperText('Select one or more halls for this event')
                            ->columnSpanFull()
                            ->rules([
                                'required',
                                'array',
                                'min:1',
                                function () {
                                    return function (string $attribute, $value, \Closure $fail) {
                                        $startsAt = request()->input('starts_at');
                                        $endsAt = request()->input('ends_at');
                                        $eventId = request()->route('record');

                                        if (! $startsAt || ! $endsAt || ! is_array($value)) {
                                            return;
                                        }

                                        foreach ($value as $hallId) {
                                            $hasConflict = \App\Models\Event::where('id', '!=', $eventId)
                                                ->whereHas('halls', function ($query) use ($hallId) {
                                                    $query->where('hall_id', $hallId);
                                                })
                                                ->whereIn('status', ['draft', 'active'])
                                                ->where(function ($q) use ($startsAt, $endsAt) {
                                                    $q->whereBetween('starts_at', [$startsAt, $endsAt])
                                                        ->orWhereBetween('ends_at', [$startsAt, $endsAt])
                                                        ->orWhere(function ($q) use ($startsAt, $endsAt) {
                                                            $q->where('starts_at', '<=', $startsAt)
                                                                ->where('ends_at', '>=', $endsAt);
                                                        });
                                                })
                                                ->exists();

                                            if ($hasConflict) {
                                                $hallName = \App\Models\Hall::find($hallId)?->name ?? 'Selected hall';
                                                $fail("{$hallName} is already booked for another event during the selected time period.");
                                                break;
                                            }
                                        }
                                    };
                                },
                            ]),

                        DateTimePicker::make('starts_at')
                            ->required()
                            ->label('Start Date & Time')
                            ->native(false)
                            ->seconds(false)
                            ->minDate(now())
                            ->displayFormat('M d, Y H:i')
                            ->columnSpanFull(),

                        DateTimePicker::make('ends_at')
                            ->required()
                            ->label('End Date & Time')
                            ->native(false)
                            ->seconds(false)
                            ->minDate(fn (Get $get) => $get('starts_at') ?? now())
                            ->displayFormat('M d, Y H:i')
                            ->after('starts_at')
                            ->helperText('Must be after start time')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
