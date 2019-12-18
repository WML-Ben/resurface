<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderStatus extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $table = 'order_status';

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

    public function scopeProposalStatus($query)
    {
        return $query->whereIn('id', [1, 2, 3, 4, 10]);
    }

    public function scopeWorkOrderStatus($query)
    {
        return $query->whereIn('id', [5, 6, 7, 8, 9]);
    }

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

    static public function proposalStatusCB($default = [])
    {
        $items = self::proposalStatus()->orderBy('d_sort')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function workOrderStatusCB($default = [])
    {
        $items = self::workOrderStatus()->orderBy('d_sort')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }


}
