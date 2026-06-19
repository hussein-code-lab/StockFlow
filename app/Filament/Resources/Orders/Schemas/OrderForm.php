<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer & Order Details')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('order_number')
                            ->default('ORD-' . strtoupper(uniqid()))
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        TextInput::make('customer_name')
                            ->required(),
                    ]),

                Section::make('Order Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->columns(3)
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::pluck('name', 'id'))
                                    ->reactive()
                                    ->afterStateUpdated(
                                        fn($state, callable $set) =>
                                        $set('price', Product::find($state)?->price ?? 0)
                                    )
                                    ->required(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required(),

                                TextInput::make('price')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->label('Unit Price'),
                            ])
                    ])->columnSpanFull()
            ]);
    }
}
