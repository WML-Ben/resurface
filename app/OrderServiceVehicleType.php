<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderServiceVehicleType extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $table = 'order_service_vehicle_types';

    protected $fillable = [
        'order_service_id',
        'vehicle_type_id',
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
        'order_service_vehicle_types.order_service_id|order_services.order_number',
        'order_service_vehicle_types.vehicle_type_id|vehicle_types.name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'childModels' => [
            'orderService' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'vehicleType'  => [
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

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
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
