<?php namespace App;

use SortableTrait;
use SearchTrait;

class Privilege extends BaseModel
{

    use SortableTrait, SearchTrait;
    
    public $timestamps = false;

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

    public function roles()
    {
        return $this->belongsToMany(Role::class);
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

    /** Accessors and Mutators */



    /** Methods */

}
