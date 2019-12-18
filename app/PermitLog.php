<?php namespace App;

use SortableTrait;
use SearchTrait;

class PermitLog extends BaseModel
{
    use SortableTrait, SearchTrait;
    
    public $dates = ['permitted_at'];

    protected $fillable = [
        'work_order_id',
        'hours',
        'amount',
        'fee',
        'note',
        'permitted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'hours',
        'amount',
        'fee',
        'created_at',
        'permit_logs.work_order_id|work_orders.name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'note'        => 'LIKE',
        'created_at'  => 'LIKE',
        'category' => [
            'workOrder'     => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'createdBy' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'updatedBy' => [
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

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
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

    public function getHoursRateAttribute()
    {
        return is_numeric($this->attributes['hours']) ? $this->attributes['hours'] / 100 : 0;
    }

    public function setHoursAttribute($value)
    {
        $this->attributes['hours'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getFeeAttribute()
    {
        return is_numeric($this->attributes['fee']) ? $this->attributes['fee'] / 100 : 0;
    }

    public function setFeeAttribute($value)
    {
        $this->attributes['fee'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getFeeCurrencyAttribute()
    {
        return is_numeric($this->attributes['fee']) ? '$' . sprintf('%s', number_format($this->attributes['fee'] / 100, 2)) : null;
    }


    /** Methods */


}
