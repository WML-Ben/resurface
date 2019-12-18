<?php namespace App;

use SortableTrait;
use SearchTrait;

class PermitStatus extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $table = 'permit_status';

    public $timestamps = false;

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


    /** scopes */


    /** Accessors and Mutators */


    /** Methods */

    static public function statusCB($default = [])
    {
        $items = self::orderBy('d_sort')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }


}
