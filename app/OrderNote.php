<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderNote extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $dates = ['remainded_at'];

    protected $fillable = [
        'order_id',
        'order_service_id',
        'note',
        'remainded_at',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    public $sortable = [
        'remainded_at',
        'created_at',
        'order_notes.order_id|orders.order_number',
        'order_notes.order_service_id|order_services.name',
        'order_notes.created_by|users.first_name',
    ];

    public $searchable = [
        'name' => 'LIKE',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->created_at = now(session()->get('timezone'));
            $model->created_by = auth()->user()->id;
        });

        self::saving(function($model) {
            if (empty($model->order_service_id)) {
                $model->order_service_id = null;
            }

            $model->updated_at = now(session()->get('timezone'));
            $model->updated_by = auth()->user()->id;
        });
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

    public function service()
    {
        return $this->belongsTo(OrderService::class, 'order_service_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    /** scopes */

    /** Accessors and Mutators */


    /** Methods */



}
