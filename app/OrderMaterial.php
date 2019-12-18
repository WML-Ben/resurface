<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderMaterial extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'material_id',
        'name',
        'cost',
        'alt_cost',
    ];

    public $sortable = [
        'name',
        'cost',
        'alt_cost',
        'order_materials.order_id|orders.name',
        'order_materials.order_id|orders.order_numberIndex',
        'order_materials.material_id|materials.name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'cost'        => 'LIKE',
        'childModels' => [
            'order'    => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'material' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
        ],
    ];

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

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
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

    public function getAltCostAttribute()
    {
        return is_numeric($this->attributes['alt_cost']) ? $this->attributes['alt_cost'] / 100 : 0;
    }

    public function setAltCostAttribute($value)
    {
        $this->attributes['alt_cost'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getAltCostCurrencyAttribute()
    {
        return is_numeric($this->attributes['alt_cost']) ? '$' . sprintf('%s', number_format($this->attributes['alt_cost'] / 100, 2)) : null;
    }


    /** Methods */

    static public function materialsCB($orderId, $default = [])
    {
        $items = self::where('order_id', $orderId)->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

}
