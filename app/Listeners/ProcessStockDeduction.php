<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\InventoryMovement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessStockDeduction implements ShouldQueue
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
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        Log::info($order);

        DB::transaction(function() use ($order) {
            $totalAmount = 0;

            foreach($order->items as $item) {

                $totalAmount += ($item->quantity * $item->price);

                InventoryMovement::create([
                    'product_id'     => $item->product_id,
                    'user_id'        => auth()->user()->id ?? null,
                    'type'           => 'out',
                    'quantity'       => -$item->quantity,
                    'reference_type' => get_class($order),
                    'reference_id'   => $order->id,
                ]);
            }

            $order->update([
                'status' => 'processing',
                'total_amount' => $totalAmount
            ]);
        });
    }
}
