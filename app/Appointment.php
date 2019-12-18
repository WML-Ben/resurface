<?php namespace App;

use App\Scopes\ApointmentScope;

class Appointment extends CalendarEvent
{
    protected $table = 'calendar_events';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ApointmentScope);
    }

    /** relationships */

    
    /** scopes */

    public function scopeOwn($query)
    {
        return $query->where('user_id', auth()->user()->id)->orWhereHas('property', function($q){
            $q->where('manager_id', auth()->user()->id);
        });
    }

    public function scopeBasedOnRole($query)
    {
        if (auth()->user()->hasRoles(['admin', 'office_worker'])) {         // can see all
            return $query;
        } else {
            return $query->where(function($q){
                $q->orWhere('user_id', auth()->user()->id)
                    ->orWhere('created_by', auth()->user()->id);
            });
        }
    }


    /** Methods */




}
