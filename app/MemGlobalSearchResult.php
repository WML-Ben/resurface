<?php namespace App;

use SortableTrait;
use SearchTrait;

class MemGlobalSearchResult extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $sortable = [
        'name',
        'email',
        'phone',
        'type',
    ];

    public $searchable = [
        'name'  => 'LIKE',
        'email' => 'LIKE',
        'phone' => 'LIKE',
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

}
