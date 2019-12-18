<?php namespace App;

use SortableTrait;
use SearchTrait;

class Location extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'manager_id',
        'name',
        'address',
        'address_2',
        'city',
        'zipcode',
        'state_id',
        'country_id',
        'phone',
    ];

    public $sortable = [
        'name',
        'phone',
        'locations.manager_id|users.first_name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'phone'       => 'LIKE',
        'childModels' => [
            'state'   => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'country' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'manager' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
        ],
    ];

    protected static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            if (empty($model->state_id)) {
                $model->state_id = 3930;
            }
            if (empty($model->country_id)) {
                $model->country_id = 231;
            }
        });
    }

    public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }

    /** relationships */

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }


    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /** scopes */


    /** Methods, Accessor(get) and Mutators(set) */

    static public function locationsCB($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function jsonHtmlLocationssCB($defaultEmpty = true)
    {
        $locations = self::orderBy('name')->get();

        $items = [];

        if (!empty($defaultEmpty)) {
            $items[] = [
                'id'    => 0,
                'text'  => '',
                'html'  => '',
                'title' => '',
            ];
        }

        /**
         *  - Search while typing will be condiucted on "text" content.
         *  - After selecting option, "text" content will be shown (can be html for styling).
         *  - Options are the "html" content
         *  - "title" will be shown when hovering the option
         */

        foreach ($locations as $location) {
            $items[] = [
                'id'    => $location->id,
                'text'  => $location->name,
                'html'  => '<div class="select2-option-html"><div class="first-line">' . $location->name . '</div><div class="second-line">' . $location->short_location . '</div></div>',
                'title' => '',
            ];
        }

        return json_encode($items);
    }

}
