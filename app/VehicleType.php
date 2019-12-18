<?php namespace App;

use SortableTrait;
use SearchTrait;

class VehicleType extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'rate',
        'd_sort',
    ];

    public $sortable = [
        'name',
        'rate',
        'd_sort',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'description' => 'LIKE',
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

    public function serviceCategories()
    {
        return $this->belongsToMany(ServiceCategory::class)->withPivot('is_default');
    }

    /** scopes */


    /** Accessor(get) and Mutators(set) */


    public function getRateAttribute()
    {
        return is_numeric($this->attributes['rate']) ? $this->attributes['rate'] / 100 : 0;
    }

    public function setRateAttribute($value)
    {
        $this->attributes['rate'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getRateFloatAttribute()
    {
        return is_numeric($this->attributes['rate']) ? sprintf('%s', number_format($this->attributes['rate'] / 100, 2)) : null;
    }

    public function getRateCurrencyAttribute()
    {
        return is_numeric($this->attributes['rate']) ? '$' . sprintf('%s', number_format($this->attributes['rate'] / 100, 2)) : null;
    }
    
    /** Methods */

    static public function typesCB($default = [])
    {
        $items = self::orderBy('d_sort')->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

}
