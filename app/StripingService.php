<?php namespace App;

use SortableTrait;
use SearchTrait;

class StripingService extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'striping_service_category_id',
        'name',
        'd_sort',
    ];

    public $sortable = [
        'name',
        'd_sort',
        'strinping_services.striping_service_category_id|strinping_service_categories.name',
    ];

    public $searchable = [
        'name'  => 'LIKE',
        'childModels' => [
            'stripingServiceCategory'  => [
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

    public function stripingServiceCategory()
    {
        return $this->belongsTo(StripingServiceCategory::class)->orderBy('name');
    }

    public function stripingVendors()
    {
        return $this->belongsToMany(StripingVendor::class)->withPivot('price');
    }


    /** scopes */


    /** Accessors and Mutators */

    public function getPriceAttribute()
    {
        return $this->pivot->price / 100;
    }

    public function getPriceCurrencyAttribute()
    {
        $price = $this->pivot->price;

        return is_numeric($price) ? '$' . sprintf('%s', number_format($price / 100, 2)) : null;
    }

    /** Methods */

    static public function categoryServicesCB($stripingServiceCategoryId, $default = [])
    {
        $items = self::where('striping_service_category_id', $stripingServiceCategoryId)->orderBy('d_sort')->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

}
