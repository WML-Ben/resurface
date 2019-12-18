<?php namespace App;

use SortableTrait;
use SearchTrait;

class Vehicle extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $dates = ['purchased_at'];

    protected $fillable = [
        'type_id',
        'location_id',
        'name',
        'description',
        'vin_number',
        'purchased_at',
        'disabled',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'name',
        'disabled',
        'created_at',
        'vehicles.type_id|vehicle_types.name',
        'vehicles.location_id|vehicle_types.name',

    ];

    public $searchable = [
        'name'        => 'LIKE',
        'created_at'  => 'LIKE',
        'description' => 'LIKE',
        'childModels' => [
            'vehicleType' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'location'    => [
                'fields' => [
                    'address' => 'LIKE',
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

        self::creating(function ($model) {
            $model->created_at = now(session()->get('timezone'));
            $model->created_by = auth()->user()->id;
        });

        self::updating(function ($model) {
            $model->updated_at = now(session()->get('timezone'));
            $model->updated_by = auth()->user()->id;
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

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
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

    public function scopeShowAllFilter($query, $showAll = 0)
    {
        return !empty($showAll) ? $query : $query->active();
    }


    /** Accessors and Mutators */

    public function getHtmlPurchasedAtAttribute()
    {
        return !empty($this->purchased_at) ? $this->purchased_at->format('M. d, Y') : null;
    }


    /** Methods */

    static public function perTypesCB($typeId = null, $default = [])
    {
        $query = self::orderBy('d_sort')->orderBy('name');

        if (!empty($typeId)) {
            $query->where('type_id', $typeId);
        }

        $items = $query->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

}
