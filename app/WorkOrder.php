<?php namespace App;

use App\Scopes\WorkOrderScope;

class WorkOrder extends Order
{
    protected $table = 'orders';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new WorkOrderScope);
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



    /** scopes */

    public function scopeWorkOrderProcessing($query)
    {
        return $query->where('status_id', 9);
    }

    public function scopeWorkOrderActive($query)
    {
        return $query->where('status_id', 5);
    }

    public function scopeWorkOrderBilling($query)
    {
        return $query->where('status_id', 6);
    }

    public function scopeWorkOrderCancelled($query)
    {
        return $query->where('status_id', 7);
    }

    public function scopeWorkOrderBilled($query)
    {
        return $query->where('status_id', 8);
    }


    /** Accessors and Mutators */


    /** Methods */


}
