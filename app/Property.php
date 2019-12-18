<?php namespace App;

use SortableTrait;
use SearchTrait;

class Property extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $table = 'properties';
    
    protected $fillable = [
        'owner_id',
        'company_id',
        'manager_id',
        'name',
        'email',
        'phone',
        'address',
        'address_2',
        'city',
        'zipcode',
        'state_id',
        'country_id',
        'parcel_number',
        'comment',
        'qualified',
        'disabled',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'name',
        'email',
        'phone',
        'parcel_number',
        'properties.company_id|companies.name',
        'properties.owner_id|users.first_name',
        'properties.manager_id|users.first_name',
    ];

    public $searchable = [
        'name' => 'LIKE',
        'email' => 'LIKE',
        'phone' => 'LIKE',
        'parcel_number' => 'LIKE',
        'childModels' => [
            'state'      => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'country'      => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'owner'      => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'company'      => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'manager'      => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'createdBy'   => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'updatedBy'   => [
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

        self::creating(function($model) {
            $model->created_at = now(session()->get('timezone'));
            $model->created_by = auth()->user()->id;
        });

        self::updating(function($model) {
            $model->updated_at = now(session()->get('timezone'));
            $model->updated_by = auth()->user()->id;
        });

        self::saving(function($model) {
            if (empty($model->disabled)) {
                $model->disabled = 0;
            }
            if (empty($model->manager_id)) {
                $model->manager_id = null;
            }
            if (empty($model->owner_id)) {
                $model->owner_id = null;
            }
            if (empty($model->company_id)) {
                $model->company_id = null;
            }
            if (empty($model->qualified)) {
                $model->qualified = 0;
            }
            if (empty($model->state_id)) {
                $model->state_id = 3930;
            }
            if (empty($model->country_id)) {
                $model->country_id = 231;
            }

            \Cache::forget('json_html_properties_cb');
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

    public function owner()
    {
        return $this->belongsTo(Contact::class, 'owner_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

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


    /** Accessors and Mutators */

    // property->name ?? '' !!}{{ $appointment->property->short_location_one_line

    public function getHtmlNameAndShortLocationAttribute()
    {
        $arr = [];

        if (!empty($this->name)) {
            $arr[] = '<span class="info-first-row">'. $this->name .'</span>';
        }
        if (!empty($this->short_location_one_line)) {
            $arr[] = '<span class="info-other-rows">'. $this->short_location_one_line .'</span>';
        }

        return !empty($arr) ? implode('<br>', $arr) : null;
    }


    /** Methods */


    static public function jsonHtmlPropertiesCB($defaultEmpty = true)
    {
        $properties = self::orderBy('name')->get();

        $items = [];

        if (!empty($defaultEmpty)) {
            $items[] = [
                'id'    => '',
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

        foreach ($properties as $property) {
            $items[] = [
                'id'    => $property->id,
                'text'  => $property->name,
                'html'  => '<div class="select2-option-html"><div class="first-line">' . $property->name . '</div><div class="second-line">' . $property->short_location . '</div></div>',
                'title' => '',
            ];
        }

        return json_encode($items);
    }

    static public function jsonHtmlPropertyOwnersCB($defaultEmpty = true)
    {
        $contacts = \App\ContactNew::where('is_delete', 0)->where('category_id', 16)->orderBy('first_name')->get();

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

        foreach ($contacts as $contact) {
            $secondLine = [];

            if (!empty($contact->email)) {
                $secondLine[] = $contact->email;
            }
            if (!empty($contact->phone)) {
                $secondLine[] = $contact->phone;
            }

            $secondLine = !empty($secondLine) ? '<div class="second-line">'. implode('<br>', $secondLine) .'</div>' : '';

            $items[] = [
                'id'    => $contact->id,
                'text'  => $contact->first_name.' '.$contact->last_name,
                'html'  => '<div class="select2-option-html"><div class="single-line">' . $contact->first_name.' '.$contact->last_name . '</div>'. ($secondLine ?? '') .'</div>',
                'title' => '',
            ];
        }

        return json_encode($items);
    }

}
