<?php

namespace App\Filament\Resources\Submissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event & Booth Information')
                    ->description('Select the event, hall, and booth details')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('event_id')
                            ->relationship('event', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Event')
                            ->columnSpanFull(),

                        Select::make('hall_id')
                            ->relationship('hall', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Hall')
                            ->columnSpanFull(),

                        TextInput::make('booth_id')
                            ->required()
                            ->maxLength(50)
                            ->label('Booth ID')
                            ->placeholder('e.g., A1, B2')
                            ->helperText('The booth identifier from the floor map')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Contact Information')
                    ->description('Submission contact details')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('phone_number')
                            ->required()
                            ->tel()
                            ->label('Phone Number')
                            ->placeholder('+965 XXXX XXXX')
                            ->columnSpanFull(),

                        TextInput::make('email')
                            ->email()
                            ->label('Email Address')
                            ->placeholder('example@domain.com')
                            ->columnSpanFull(),

                        TextInput::make('company_name')
                            ->maxLength(255)
                            ->label('Company Name')
                            ->placeholder('Your company name')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Submission Status')
                    ->description('Current status of the booth submission')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('status')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approve',
                                'rejected' => 'Reject',
                            ])
                            ->default('pending')
                            ->native(false)
                            ->label('Status')
                            ->helperText('Pending: Awaiting review. Approved: Confirmed. Rejected: Declined.')
                            ->live()
                            ->columnSpanFull(),

                        Select::make('payment_method')
                            ->options([
                                'wire_transfer' => 'Wire Transfer',
                                'link' => 'Link',
                            ])
                            ->native(false)
                            ->label('Payment Method')
                            ->helperText('Select how payment will be processed')
                            ->visible(fn ($get): bool => $get('status') === 'approved')
                            ->required(fn ($get): bool => $get('status') === 'approved')
                            ->live()
                            ->columnSpanFull(),

                        Textarea::make('comment')
                            ->label(function ($get): string {
                                if ($get('status') === 'rejected') {
                                    return 'Rejection Reason';
                                }
                                if ($get('status') === 'approved' && $get('payment_method') === 'link') {
                                    return 'Payment Link';
                                }

                                return 'Comment/Notes';
                            })
                            ->helperText(function ($get): string {
                                if ($get('status') === 'rejected') {
                                    return 'Provide a reason for rejecting this submission';
                                }
                                if ($get('status') === 'approved' && $get('payment_method') === 'link') {
                                    return 'Enter the payment link URL';
                                }

                                return 'Optional notes or additional information';
                            })
                            ->rows(4)
                            ->visible(fn ($get): bool => in_array($get('status'), ['approved', 'rejected']))
                            ->required(fn ($get): bool => in_array($get('status'), ['approved', 'rejected']))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
