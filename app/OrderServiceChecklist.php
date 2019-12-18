<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderServiceChecklist extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $table = 'order_service_checklists';

    public $protected = ['reported_at'];

    protected $fillable = [
        'order_service_id',
        'trencher',
        'hand_saw',
        'plate_compactor',
        'shovel',
        'pick',
        'forms_pins',
        'roller',
        'street_saw',
        'hand_finishing_tools',
        'orange_paint',
        'gasoline',
        'rake',
        'tack',
        'blower',
        'wands',
        'brush_broom',
        'string_line',
        'barricades',
        'check_tanker',
        'check_tires',
        'note',
        'reported_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'name',
        'd_sort',
        'order_service_checklists.order_service_id|order_services.order_number'
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'childModels' => [
            'orderService' => [
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
