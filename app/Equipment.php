<?php namespace App;

use SortableTrait;
use SearchTrait;

class Equipment extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $table = 'equipments';

    protected $fillable = [
        'rate_type_id',
        'name',
        'description',
        'cost',
        'min_cost',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'name',
        'cost',
        'is_active',
        'created_at',
        'equipments.rate_type_id|equipment_rate_types.name',
        'equipments.created_by|users.first_name',
        'equipments.updated_by|users.first_name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'created_at'  => 'LIKE',
        'description' => 'LIKE',
        'childModels' => [
            'rateType'  => [
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

    public function rateType()
    {
        return $this->belongsTo(EquipmentRateType::class, 'rate_type_id');
    }

    public function serviceCategories()
    {
        return $this->belongsToMany(ServiceCategory::class)->withPivot('is_default');
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
