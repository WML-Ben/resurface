<?php namespace App;

use SortableTrait;
use SearchTrait;

class CompanyCategory extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'do_not_delete',
    ];

    public $sortable = [
        'name',
        'do_not_delete',
    ];

    public $searchable = [
        'name',
        'description',
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


    /** scopes */


    /** Accessors and Mutators */


    /** Methods */

    static public function categoriesCB($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

}
