<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\InventoryMovement;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {
        $initialStock = $this->form->getState()['initial_stock'] ?? 0;

        if ($initialStock > 0) {
            InventoryMovement::create([
                'product_id' => $this->record->id,
                'user_id' => auth()->id(),
                'type' => 'in',
                'quantity' => $initialStock,
                'reference_type' => get_class($this->record),
                'reference_id' => $this->record->id,
            ]);
        }
    }
}
