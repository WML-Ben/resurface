<?php namespace App;

use SortableTrait;
use SearchTrait;

class VehicleLog extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'vehicle_id',
        'type_id',
        'note',
        'amount',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'note',
        'amount',
        'vehicle_logs.vehicle_id|vehicles.name',
        'vehicle_logs.type_id|vehicle_log_types.name',
        'vehicle_logs.created_by|users.first_name',
        'vehicle_logs.updated_by|users.first_name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'created_at'  => 'LIKE',
        'description' => 'LIKE',
        'childModels' => [
            'type' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'vehicle' => [
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

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function type()
    {
        return $this->belongsTo(VehicleLogType::class, 'type_id');
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
