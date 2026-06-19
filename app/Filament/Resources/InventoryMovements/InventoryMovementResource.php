<?php

namespace App\Filament\Resources\InventoryMovements;

use App\Filament\Resources\InventoryMovements\Pages\ManageInventoryMovements;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoryMovementResource extends Resource
{
    protected static ?string $model = InventoryMovement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->options(Product::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('type')
                    ->required()
                    ->options([
                        'in' => 'Stock in',
                        'adjustment' => 'Adjustment'
                    ]),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->searchable(),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state)
                    {
                        'in' => 'success',
                        'out' => 'danger',
                        'adjustment' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state)
                    {
                        'in' => 'Stock in',
                        'out' => 'Out',
                        'adjustment' => 'Adjustment',
                        default => $state,
                    }),

                TextColumn::make('quantity')
                    ->formatStateUsing(fn(int $state) => $state > 0 ? "+{$state}" : $state)
                    ->color(fn(int $state) => $state > 0 ? 'success' : 'danger')
                    ->alignCenter(),

                TextColumn::make('reference_id')
                    ->formatStateUsing(function ($record) {
                        if ($record->reference_type === Order::class)
                        {
                            return $record->reference?->order_number ?? $record->reference_id;
                        }
                        return 'Add Manually';
                    }),

                TextColumn::make('user.name')
                    ->default('Default System'),

                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInventoryMovements::route('/'),
        ];
    }
}
