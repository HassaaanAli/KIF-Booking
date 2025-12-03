<?php

namespace App\Filament\Resources\Halls\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HallForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Hall Name')
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('floor_map')
                    ->collection('floor_map')
                    ->acceptedFileTypes(['image/svg+xml'])
                    ->maxFiles(1)
                    ->label('Floor Map (SVG)')
                    ->helperText('Upload an SVG file for the hall floor map')
                    ->image()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
