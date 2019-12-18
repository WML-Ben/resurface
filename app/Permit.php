<?php namespace App;

use SortableTrait;
use SearchTrait;

class Permit extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'order_id',
        'order_service_id',
        'permit_type_id',
        'permit_status_id',
        'number',
        'county',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'permits.order_id|orders.name',
        'permits.order_service_id|order_services.name',
        'permits.permit_type_id|permit_types.name',
        'permits.permit_status_id|permit_status.name',
        'permits.created_by|users.first_name',
        'permits.updated_by|users.first_name',
        'number',
        'county',
        'created_at',
    ];

    public $searchable = [
        'number'      => 'LIKE',
        'county'      => 'LIKE',
        'created_at'  => 'LIKE',
        'childModels' => [
            'status'       => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'type'         => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'order'        => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'orderService' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'createdBy'    => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'updatedBy'    => [
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

    public function status()
    {
        return $this->belongsTo(PermitStatus::class, 'permit_status_id');
    }

    public function type()
    {
        return $this->belongsTo(PermitTypes::class, 'permit_type_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function orderService()
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

    public function getIsNotApprovedAttribute()
    {
        return $this->permit_status_id != 1;
    }

    public function getFee1Attribute()
    {
        return is_numeric($this->attributes['fee_1']) ? $this->attributes['fee_1'] / 100 : 0;
    }

    public function setFee1Attribute($value)
    {
        $this->attributes['fee_1'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getFee1CurrencyAttribute()
    {
        return is_numeric($this->attributes['fee_1']) ? '$' . sprintf('%s', number_format($this->attributes['fee_1'] / 100, 2)) : null;
    }

    public function getFee2Attribute()
    {
        return is_numeric($this->attributes['fee_2']) ? $this->attributes['fee_2'] / 200 : 0;
    }

    public function setFee2Attribute($value)
    {
        $this->attributes['fee_2'] = is_numeric($value) ? intval($value * 200) : 0;
    }

    public function getFee2CurrencyAttribute()
    {
        return is_numeric($this->attributes['fee_2']) ? '$' . sprintf('%s', number_format($this->attributes['fee_2'] / 200, 2)) : null;
    }

    /** Methods */


}
