<?php

namespace App\Listeners;

use App\Events\OrderUpdated;
use App\Models\InventoryMovement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class ProcessStockAdjustment implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderUpdated $event): void
    {
        $order = $event->order;

        DB::transaction(function () use ($order) {

            $order->inventoryMovements()->delete();

            $totalAmount = 0;

            foreach ($order->items as $item) {
                $totalAmount += ($item->quantity * $item->price);

                InventoryMovement::create([
                    'product_id'     => $item->product_id,
                    'user_id'        => auth()->id() ?? null,
                    'type'           => 'adjustment',
                    'quantity'       => -$item->quantity,
                    'reference_type' => get_class($order),
                    'reference_id'   => $order->id,
                ]);
            }

            $order->update([
                'total_amount' => $totalAmount,
            ]);
        });
    }
}
