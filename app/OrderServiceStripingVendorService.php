<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderServiceStripingVendorService extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'order_service_id',
        'striping_vendor_id',
        'striping_service_id',
        'quantity',
        'price',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'quantity',
        'price',
        'order_service_strinping_vendor_services.order_service_id|order_services.order_number',
        'order_service_strinping_vendor_services.striping_vendor_id|striping_vendors.name',
        'order_service_strinping_vendor_services.striping_service_id|striping_services.name',
    ];

    public $searchable = [
        'quantity'    => 'LIKE',
        'price'       => 'LIKE',
        'childModels' => [
            'orderService'    => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'stripingVendor'  => [
                'fields' => [
                    'fist_name' => 'LIKE',
                    'last_name' => 'LIKE',
                ],
            ],
            'stripingService' => [
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

    public function stripingVendor()
    {
        return $this->belongsTo(StripingVendor::class, 'striping_vendor_id');
    }

    public function stripingService()
    {
        return $this->belongsTo(StripingService::class, 'striping_service_id');
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

    public function getPriceAttribute()
    {
        return is_numeric($this->attributes['price']) ? $this->attributes['price'] / 100 : 0;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getPriceFloatAttribute()
    {
        return is_numeric($this->attributes['price']) ? sprintf('%s', number_format($this->attributes['price'] / 100, 2)) : null;
    }

    public function getPriceCurrencyAttribute()
    {
        return is_numeric($this->attributes['price']) ? '$' . sprintf('%s', number_format($this->attributes['price'] / 100, 2)) : null;
    }

    /** Methods */

}
