<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderServiceEquipment extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $table = 'order_service_equipments';

    protected $fillable = [
        'order_service_id',
        'equipment_id',
        'rate_type_id',
        'name',
        'quantity',
        'days_needed',
        'hours_per_day',
        'cost',
        'min_cost',
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
        'cost',
        'min_cost',
        'order_service_equipments.order_service_id|order_services.order_number',
        'order_service_equipments.equipment_id|equipments.name',
        'order_service_equipments.rate_type_id|equipment_rate_type.name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'childModels' => [
            'orderService' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'equipment'    => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'rateType'     => [
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

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function rateType()
    {
        return $this->belongsTo(OrderService::class, 'rate_type_id');
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

    public function getCostAttribute()
    {
        return is_numeric($this->attributes['cost']) ? $this->attributes['cost'] / 100 : 0;
    }

    public function setCostAttribute($value)
    {
        $this->attributes['cost'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getCostCurrencyAttribute()
    {
        return is_numeric($this->attributes['cost']) ? '$' . sprintf('%s', number_format($this->attributes['cost'] / 100, 2)) : null;
    }

    public function getMinCostAttribute()
    {
        return is_numeric($this->attributes['min_cost']) ? $this->attributes['min_cost'] / 100 : 0;
    }

    public function setMinCostAttribute($value)
    {
        $this->attributes['min_cost'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getMinCostCurrencyAttribute()
    {
        return is_numeric($this->attributes['min_cost']) ? '$' . sprintf('%s', number_format($this->attributes['min_cost'] / 100, 2)) : null;
    }

    /** Methods */

}
