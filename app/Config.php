<?php namespace App;

use SortableTrait;
use SearchTrait;
use ToolTrait;

class Config extends BaseModel
{
    use SortableTrait, SearchTrait, ToolTrait;

    protected $table = 'config';

    public $timestamps = false;

    protected $fillable = [
        'item_key',
        'item_value',
    ];

    public $sortable = [
        'item_key',
        'item_value',
    ];

    public $searchable = [
        'item_key'   => 'LIKE',
        'item_value' => 'LIKE',
    ];

    public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }

    /** Scopes */

    public function scopeNoSystem($query)
    {
        return $query->where('system', 0);
    }

    /** Mutators and Accessors */

    /** Methods */

    public static function reload()
    {
        $confArray = self::fetch();

        session()->put('config', $confArray);
        view()->share('config', $confArray);

        $conf = (object)$confArray;

        session()->put('conf', $conf);
        view()->share('conf', $conf);

        return $conf;
    }

    public static function fetch()
    {
        $items = self::active()->get();

        $confArray = [];
        foreach ($items as $item) {
            if (!empty($item->item_key)) {
                $confArray[$item->item_key] = $item->item_value;
            }
        }

        return $confArray;
    }

}
