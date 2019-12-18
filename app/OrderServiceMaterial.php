<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderServiceMaterial extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $table = 'order_service_materials';

    protected $fillable = [
        'order_service_id',
        'material_id',
        'vendor_id',
        'amount',
        'cost',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'amount',
        'cost',
        'order_service_materials.order_service_id|order_services.order_number',
        'order_service_materials.material_id|materials.name',
        'order_service_materials.vendor_id|users.first_name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'childModels' => [
            'orderService' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'material'     => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'vendor'       => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
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

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
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

    public function getAmountAttribute()
    {
        return is_numeric($this->attributes['amount']) ? $this->attributes['amount'] / 100 : 0;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getAmountCurrencyAttribute()
    {
        return is_numeric($this->attributes['amount']) ? '$' . sprintf('%s', number_format($this->attributes['amount'] / 100, 2)) : null;
    }


    /** Methods */

}
