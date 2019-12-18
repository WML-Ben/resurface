<?php namespace App;

use SortableTrait;
use SearchTrait;

class Company extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'category_id',
        'name',
        'email',
        'phone',
        'address',
        'address_2',
        'city',
        'zipcode',
        'state_id',
        'country_id',
        'billing_address',
        'billing_address_2',
        'billing_city',
        'billing_zipcode',
        'billing_state_id',
        'billing_country_id',
        'alt_phone',
        'alt_email',
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
        'companies.category_id|company_categories.name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'email'       => 'LIKE',
        'phone'       => 'LIKE',
        'childModels' => [
            'state'    => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'country'  => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'category' => [
                'fields' => [
                    'name' => 'LIKE',
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
            if (empty($model->qualified)) {
                $model->qualified = 0;
            }
            if (empty($model->state_id)) {
                $model->state_id = 3930;
            }
            if (empty($model->country_id)) {
                $model->country_id = 231;
            }
            if (!empty($model->above_as_billing_address)) {
                $model->billing_address = $model->address;
                $model->billing_address_2 = $model->address_2;
                $model->billing_city = $model->city;
                $model->billing_zipcode = $model->zipcode;
                $model->billing_state_id = $model->state_id;
                $model->billing_country_id = $model->country_id;
            }

            if ($model->category_id == 7) {
                \Cache::forget('json_html_property_management_companies_cb');
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

    public function category()
    {
        return $this->belongsTo(CompanyCategory::class, 'category_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function billingState()
    {
        return $this->belongsTo(State::class, 'billing_state_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /** scopes */

    public function scopePropertyManagementCompany($query)
    {
        return $query->where('category_id', 7);    // Property Management Company
    }

    /** Accessors and Mutators */


    /** Methods */

    static public function companiesCB($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function propertyManagementCompaniesCB($default = [])
    {
        $items = self::propertyManagementCompany()->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function jsonHtmlCompaniesCB($defaultEmpty = true)
    {
        $companies = self::orderBy('name')->get();

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

        foreach ($companies as $company) {
            $items[] = [
                'id'    => $company->id,
                'text'  => $company->name,
                'html'  => '<div class="select2-option-html"><div class="first-line">' . $company->name . '</div><div class="second-line">' . $company->short_location . '</div></div>',
                'title' => '',
            ];
        }

        return json_encode($items);
    }

    static public function jsonHtmlPropertyManagementCompaniesCB($defaultEmpty = true)
    {
        $companies = self::propertyManagementCompany()->orderBy('name')->get();

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

        foreach ($companies as $company) {
            $items[] = [
                'id'    => $company->id,
                'text'  => $company->name,
                'html'  => '<div class="select2-option-html"><div class="first-line">' . $company->name . '</div><div class="second-line">' . $company->short_location . '</div></div>',
                'title' => '',
            ];
        }

        return json_encode($items);
    }

    public function getUsersCB($default = [])
    {
        if (!$this->users->count()) {
            return null;
        }

        $items = $this->users()->orderBy('first_name')->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return \App\BaseModel::mergeAssoc($default, $items);
        }

        return $items;
    }


}
