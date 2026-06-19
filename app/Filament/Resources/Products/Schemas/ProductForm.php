<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('sku')
                        ->label('SKU')
                        ->required(),
                    TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->prefix('$'),
                    Textarea::make('description')
                        ->columnSpanFull(),
                    TextInput::make('alert_level')
                        ->required()
                        ->numeric()
                        ->default(10),
                ])
            ]);
    }
}
