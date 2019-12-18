<?php namespace App;

use SortableTrait;
use SearchTrait;

class CalendarEvent extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $dates = ['started_at', 'ended_at'];

    protected $fillable = [
        'user_id',
        'type_id',
        'property_id',
        'started_at',
        'ended_at',
        'name',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'name',
        'started_at',
        'ended_at',
        'calendar_events.user_id|users.first_name',
        'calendar_events.type_id|calendar_event_types.name',
        'calendar_events.property_id|properties.name',
        'calendar_events.property_id|properties-id-manager_id|users.first_name',
        'calendar_events.created_by|users.first_name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'description' => 'LIKE',
        'created_at'  => 'LIKE',
        'childModels' => [
            'user' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'type' => [
                'fields' => [
                    'name' => 'LIKE',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(CalendarEventType::class, 'type_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
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

    public function scopeAppointment($query)
    {
        return $query->where('type_id', 1);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('started_at', '=', now(session()->get('timezone'))->toDateString());
    }

    public function scopeTodayOn($query)
    {
        return $query->whereDate('started_at', '>=', now(session()->get('timezone'))->toDateString());
    }

    public function scopeOwn($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }



    /** Accessors and Mutators */

    public function getHtmlStartedAtAttribute()
    {
        return !empty($this->started_at) ? $this->started_at->format('M. d, Y') . '<br>' . $this->started_at->format('g:i A') : null;
    }

    public function getHtmlEndedAtAttribute()
    {
        return !empty($this->ended_at) ? $this->ended_at->format('M. d, Y') . '<br>' . $this->ended_at->format('g:i A') : null;
    }


    /** Methods */


}
