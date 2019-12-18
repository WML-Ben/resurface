<?php namespace App;

use SortableTrait;
use SearchTrait;

class CategoryUser extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'user_id',
        'position_id',
    ];

    public $sortable = [
        'category_users.company_id|companies.name',
        'category_users.position_id|positions.name',
    ];

    public $searchable = [
        'childModels' => [
            'company'      => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'position' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
        ],
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /** scopes */


    /** Accessors and Mutators */


    /** Methods */

}
