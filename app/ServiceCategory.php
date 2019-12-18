<?php namespace App;

use SortableTrait;
use SearchTrait;

class ServiceCategory extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
		'id',
        'name',
		'file_attached',
		'files_id',
        'color',
		'order_sort',
        'd_sort',
    ];

    public $sortable = [
        'name',
		'order_sort',
        'color',
        'd_sort',
    ];

    public $searchable = [
        'name'  => 'LIKE',
        'color' => 'LIKE',
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

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function vehicleTypes()
    {
        return $this->belongsToMany(VehicleType::class)->withPivot('is_default');
    }

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class)->withPivot('is_default');
    }


    /** scopes */


    /** Accessors and Mutators */


    /** Methods */

    public function vehicleTypesCB($default = [])
    {
        if (!$this->vehicleTypes()->count()) {
            return !empty($default) ? $default : null;
        }

        $items = $this->vehicleTypes()->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    public function equipmentsCB($default = [])
    {
        if (!$this->equipments->count()) {
            return !empty($default) ? $default : null;
        }

        $items = $this->equipments()->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function categoriesCB($default = [], $excludeIds = [])
    {
        $query = self::orderBy('order_sort')->orderBy('name');

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        $items = $query->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

}
