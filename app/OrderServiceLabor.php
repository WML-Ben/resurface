<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderServiceLabor extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'order_service_id',
        'labor_id',
        'name',
        'quantity',
        'days_needed',
        'hours_per_day',
        'rate',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'name',
        'quantity',
        'days_needed',
        'hours_per_day',
        'rate',
        'order_service_labors.order_service_id|order_services.order_number',
        'order_service_labors.labor_id|labors.name',
        'order_service_labors.labor_id|labors.amount',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'childModels' => [
            'orderService' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'labor'    => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
        ],
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_at = now(session()->get('timezone'));
            $model->created_by = auth()->user()->id;
        });

        self::updating(function ($model) {
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

    public function orderService()
    {
        return $this->belongsTo(OrderService::class, 'order_service_id');
    }

    public function labor()
    {
        return $this->belongsTo(Labor::class, 'labor_id');
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

    public function getRateAttribute()
    {
        return is_numeric($this->attributes['rate']) ? $this->attributes['rate'] / 100 : 0;
    }

    public function setRateAttribute($value)
    {
        $this->attributes['rate'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getRateCurrencyAttribute()
    {
        return is_numeric($this->attributes['rate']) ? '$' . sprintf('%s', number_format($this->attributes['rate'] / 100, 2)) : null;
    }

    /** Methods */

}
