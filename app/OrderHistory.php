<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderHistory extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $table = 'order_history';

    public $timestamps = false;

    public $dates = ['set_at'];

    protected $fillable = [
        'order_id',
        'type_id',
        'status_id',
        'previous_status_id',
        'set_by',
        'set_at',
        'comment',
    ];

    public $sortable = [
        'order_history.order_id|orders.name',
        'order_history.type_id|order_history_types.name',
        'order_history.status_id|order_status.name',
        'order_history.previous_status_id|order_status.name',
        'order_history.set_by|users.first_name',
        'set_at',
    ];

    public $searchable = [
        'comment'     => 'LIKE',
        'set_at'      => 'LIKE',
        'childModels' => [
            'order' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'type' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'status' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'setBy' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
        ],
    ];

    public static function boot()
    {
        static::creating(function($model) {
            $model->set_by = auth()->user()->id;
        });

        parent::boot();
    }

    public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }


    /** relationships */

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function type()
    {
        return $this->belongsTo(OrderHistoryType::class, 'type_id');
    }

    public function previousStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'previous_status_id');
    }

    public function setBy()
    {
        return $this->belongsTo(User::class, 'set_by');
    }


    /** scopes */

    public function scopeProposalStatus($query)
    {
        return $query->whereIn('id', [1, 2, 3, 10]);
    }

    public function scopeWorkOrderStatus($query)
    {
        return $query->whereIn('id', [4, 5, 6, 7, 8, 9]);
    }

    /** Accessors and Mutators */


    /** Methods */

    static public function statusCB($default = [])
    {
        $items = self::orderBy('d_sort')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function proposalStatusCB($default = [])
    {
        $items = self::proposalStatus()->orderBy('d_sort')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function workOrderStatusCB($default = [])
    {
        $items = self::workOrderStatus()->orderBy('d_sort')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }


}
