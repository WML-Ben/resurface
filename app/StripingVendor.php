<?php namespace App;

use SortableTrait;
use SearchTrait;

class StripingVendor extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'name',
        'd_sort',
    ];

    public $sortable = [
        'name',
        'd_sort',
    ];

    public $searchable = [
        'name' => 'LIKE',
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

    public function stripingServices()
    {
        return $this->belongsToMany(StripingService::class)->withPivot('price');
    }

    /** scopes */


    /** Accessors and Mutators */

    public function getPriceAttribute()
    {
        return $this->pivot->price / 100;
    }

    public function getPriceCurrencyAttribute()
    {
        $price = $this->pivot->price;

        return is_numeric($price) ? '$' . sprintf('%s', number_format($price / 100, 2)) : null;
    }


    /** Methods */

    static public function vendorsCB($default = [])
    {
        $items = self::orderBy('d_sort')->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

}
