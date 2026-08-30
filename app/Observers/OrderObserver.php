<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\AuditService;


class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        AuditService::log(
            'order.created',
            $order,
            [],
            [
                'order_number'   => $order->order_number,
                'total'          => $order->total,
                'payment_method' => $order->payment_method,
                'status'         => $order->status,
            ]
        );
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Status বদলেছে কিনা
        if ($order->isDirty('status')) {
            AuditService::log(
                'order.status_changed',
                $order,
                ['status' => $order->getOriginal('status')],
                ['status' => $order->status]
            );
        }

        // Payment status বদলেছে কিনা
        if ($order->isDirty('payment_status')) {
            AuditService::log(
                'order.payment_updated',
                $order,
                ['payment_status' => $order->getOriginal('payment_status')],
                ['payment_status' => $order->payment_status]
            );
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        AuditService::log(
            'order.cancelled',
            $order,
            ['order_number' => $order->order_number],
            []
        );
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
