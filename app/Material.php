<?php namespace App;

use SortableTrait;
use SearchTrait;

class Material extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'name',
        'cost',
        'alt_cost',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'name',
        'cost',
        'alt_cost',
        'materials.created_by|users.first_name',
        'materials.updated_by|users.first_name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'created_at'  => 'LIKE',
        'childModels' => [
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

    public function getCostFloatAttribute()
    {
        return is_numeric($this->attributes['cost']) ? sprintf('%s', number_format($this->attributes['cost'] / 100, 2)) : null;
    }

    public function getAltCostAttribute()
    {
        return is_numeric($this->attributes['alt_cost']) ? $this->attributes['alt_cost'] / 100 : 0;
    }

    public function setAltCostAttribute($value)
    {
        $this->attributes['alt_cost'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getAltCostFloatAttribute()
    {
        return is_numeric($this->attributes['alt_cost']) ? sprintf('%s', number_format($this->attributes['alt_cost'] / 100, 2)) : null;
    }

    public function getAltCostCurrencyAttribute()
    {
        return is_numeric($this->attributes['alt_cost']) ? '$' . sprintf('%s', number_format($this->attributes['alt_cost'] / 100, 2)) : null;
    }


    /** Methods */

    static public function materialsCB($default = [], $ids = null)
    {
        $query = self::orderBy('name');

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $items = $query->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function materialsAndCostsCB($ids = null)
    {
        $query = self::select(['id', 'name', 'cost', 'alt_cost'])->orderBy('name');

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $items = [];

        foreach ($query->get() as $material) {
            $items[$material->id] = $material;
        }

        return $items;
    }

}
