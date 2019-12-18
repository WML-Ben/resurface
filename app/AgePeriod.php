<?php namespace App;

use SortableTrait;
use SearchTrait;

class AgePeriod extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'initial_day',
        'final_day',
        'icon_class',
        'icon_color',
        'text_color',
        'background_color',
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

    static public function agePeriodsCB($default = [])
    {
        $items = self::orderBy('d_sort')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function getAgePeriodFromDate($date)
    {
        $daysOld = now(session()->get('timezone'))->diffInDays($date);

        return self::where(function($q) use ($daysOld){
            $q->orWhereNull('initial_day')->orWhere('initial_day', '<=', $daysOld);
        })->where(function($w) use ($daysOld){
            $w->orWhereNull('final_day')->orWhere('final_day', '>=', $daysOld);
        })->first();
    }


}
