<?php namespace App;

use SortableTrait;
use SearchTrait;

class VehicleLogType extends BaseModel {

    use SortableTrait, SearchTrait;

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


    /** Methods, Accessor(get) and Mutators(set) */

    static public function VehicleLogTypesCB($default = [])
    {
        $items = self::orderBy('d_sort')->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

}
