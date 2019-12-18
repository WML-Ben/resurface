<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderMedia extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $table = 'order_media';

    protected $fillable = [
        'order_id',
        'order_service_id',
        'media_category_id',
        'file_name',
        'original_file_name',
        'description',
        'admin_only',
    ];

    public $sortable = [
        'order_media.order_id|orders.name',
        'order_media.order_service_id|order_services.name',
        'order_media.media_category_id|media_categories.name',
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
            'orderService' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'category' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
        ],
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->created_at = now(session()->get('timezone'));
            $model->created_by = auth()->user()->id;
        });

        self::updating(function($model) {
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

    public function orderService()
    {
        return $this->belongsTo(OrderService::class, 'order_service_id');
    }

    public function mediaCategory()
    {
        return $this->belongsTo(MediaCategory::class, 'media_category_id');
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
