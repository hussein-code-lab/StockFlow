<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Events\OrderCreated;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        OrderCreated::dispatch($this->record);
    }
}
