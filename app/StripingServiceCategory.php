<?php namespace App;

use SortableTrait;
use SearchTrait;

class StripingServiceCategory extends BaseModel
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
        'name'  => 'LIKE',
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
        return $this->hasMany(StripingService::class);
    }

    /** scopes */


    /** Accessors and Mutators */


    /** Methods */

    static public function categoriesCB($default = [])
    {
        $items = self::orderBy('d_sort')->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

}
