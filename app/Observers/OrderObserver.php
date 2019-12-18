<?php

namespace App\Observers;

use App\OrderHistory;

class OrderObserver
{
    public function created($model)
    {
        $data = [
            'order_id'           => $model->id,
            'type_id'            => 1,                   // proposal created
            'status_id'          => 10,                  // draft
            'previous_status_id' => null,
            'set_by'             => auth()->user()->id,
            'set_at'             => now(session()->get('timezone')),
            'comment'            => 'New order created.',
        ];

        OrderHistory::create($data);
    }

    public function updated($model)
    {
        if (empty($model->order_number) && $model->isDirty('status_id')) {
            if (empty($model->order_number) && $model->getOriginal('status_id') == 4 && $model->status_id == 5) {
                $model->order_number = $model->generateWorkOrderNumber();
            }

            $data = [
                'order_id'           => $model->id,
                'type_id'            => 2,                   // status change
                'status_id'          => $model->status_id,
                'previous_status_id' => $model->getOriginal('status_id'),
                'set_by'             => auth()->user()->id,
                'set_at'             => now(session()->get('timezone')),
                'comment'            => 'Order updated.',
            ];

            OrderHistory::create($data);
        }
    }

}

