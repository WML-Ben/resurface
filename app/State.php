<?php namespace App;

use SortableTrait;
use SearchTrait;

class State extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'country_id',
        'short_name',
        'name',
    ];

    public $sortable = [
        'short_name',
        'name',
    ];

    public $searchable = [
        'short_name' => 'LIKE',
        'name'       => 'LIKE',
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

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    /** scopes */

    public function scopeCountry($query, $countryId, $amount = null)
    {
        $query->where('country_id', $countryId);
        if (!empty($amount)) {
            $query->take($amount);
        }

        return $query;
    }

    /** Methods, Accessor(get) and Mutators(set) */

    static public function statesCB($countryId, $default = [])
    {
        $certifications = self::where('country_id', $countryId)->orderBy('name')->pluck('name', 'id')->toArray();
        if (!empty($default)) {
            return self::mergeAssoc($default, $certifications);
        }
        return $certifications;
    }

}
