<?php namespace App;

use SortableTrait;
use SearchTrait;

class Service extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'service_category_id',
        'name',
        'description',
        'default_rate',
        'preferred_rate',
        'option',
        'text',
        'alt_text',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'name',
        'created_at',
        'services.service_category_id|service_categories.name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'created_at'  => 'LIKE',
        'description' => 'LIKE',
        'childModels' => [
            'category'  => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'createdBy' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'updatedBy' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
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

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
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


    public function getCategoryNameAttribute()
    {
        return $this->category->name ?? '';
    }


    public function getDefaultRateAttribute()
    {
        return is_numeric($this->attributes['default_rate']) ? $this->attributes['default_rate'] / 100 : 0;
    }

    public function setDefaultRateAttribute($value)
    {
        $this->attributes['default_rate'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getDefaultRateCurrencyAttribute()
    {
        return is_numeric($this->attributes['default_rate']) ? '$' . sprintf('%s', number_format($this->attributes['default_rate'] / 100, 2)) : null;
    }

    public function getPreferredRateAttribute()
    {
        return is_numeric($this->attributes['preferred_rate']) ? $this->attributes['preferred_rate'] / 100 : 0;
    }

    public function setPreferredRateAttribute($value)
    {
        $this->attributes['preferred_rate'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getPreferredRateCurrencyAttribute()
    {
        return is_numeric($this->attributes['preferred_rate']) ? '$' . sprintf('%s', number_format($this->attributes['preferred_rate'] / 100, 2)) : null;
    }


    /** Methods */

    static public function servicesCB($serviceCategoryId, $default = [])
    {
        $items = self::where('service_category_id', $serviceCategoryId)->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function servicesWithCategoryCB($default = [])
    {
        $serviceCategories = \App\ServiceCategory::whereHas('services')->with(['services' => function($q){
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $items = $default;

        foreach ($serviceCategories as $serviceCategory) {
            foreach ($serviceCategory->services as $service) {
                $items[$service->id] = $serviceCategory->name .' - '. $service->name;
            }
        }

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }


}
