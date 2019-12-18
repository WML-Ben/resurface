<?php namespace App;

use SortableTrait;
use SearchTrait;

class Country extends BaseModel {

    use SortableTrait, SearchTrait;

    protected $fillable = [
        'name',
    ];

    public $sortable = [
        'name',
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

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function members()
    {
        return $this->hasMany('App\Member');
    }

    public function states()
    {
        return $this->hasMany('App\State');
    }

    public function applications()
    {
        return $this->hasMany('App\Application');
    }

    /** scopes */

    public function scopeOrdered($query, $amount = null)
    {
        $query->orderBy('name');
        if (!empty($amount)) {
            $query->take($amount);
        }

        return $query;
    }

    /** Methods, Accessor(get) and Mutators(set) */

    static public function countriesCB($default = [], $onlyUS = true)
    {
        $query = self::orderBy('name');

        if ($onlyUS) {
            $query->where('id', 231);    // US
        }

        $items = $query->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function jsonHtmlCountriesCB()
    {
        $countries = self::orderBy('name')->get();

        $items = [];

        foreach ($countries as $country) {
            $items[] = [
                'id' => $country->id,
                'text' => '<div class="select2-text">Este es el text para <strong>'. $country->name .'</strong></div>',
                'html' => '<div class="select2-html"><span class="first-line">Nombre: '. $country->name .'<br><span class="seconnd-line">Con abreviarura: '. $country->short_name .'</span></div>',
                'title' => $country->name,
            ];
        }

        return json_encode($items);
    }


}
