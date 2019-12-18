<?php namespace App;

use SortableTrait;
use SearchTrait;

class Task extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $dates = ['due_at', 'completed_at'];

    protected $fillable = [
        'assigned_to',
        'status_id',
        'description',
        'response',
        'due_at',
        'completed_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'tasks.assigned_to|users.first_name',
        'tasks.created_by|users.first_name',
        'tasks.updated_by|users.first_name',
        'due_at',
        'completed_at',
    ];

    public $searchable = [
        'description'  => 'LIKE',
        'response'     => 'LIKE',
        'due_at'       => 'LIKE',
        'completed_at' => 'LIKE',
        'childModels'  => [
            'assignedTo' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'createdBy'  => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'updatedBy'  => [
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

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
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

    public function scopeOwn($query)
    {
        return $query->where('assigned_to', auth()->user()->id);
    }

    public function scopeBasedOnRole($query)
    {
        if (auth()->user()->hasRoles(['admin', 'office_worker'])) {         // can see all
            return $query;
        } else {
            return $query->where(function($q){
                $q->orWhere('assigned_to', auth()->user()->id)
                    ->orWhere('created_by', auth()->user()->id);
            });
        }
    }

    public function scopePending($query)
    {
        return $query->whereNull('completed_at')->whereNotNull('due_at')->where('due_at', '>', now(session()->get('timezone')));
    }

    public function scopeOverDue($query)
    {
        return $query->whereNull('completed_at')->whereNotNull('due_at')->where('due_at', '<=', now(session()->get('timezone')));
    }

    // includes both pending and overdue

    public function scopeIncomplete($query)
    {
        return $query->whereNull('completed_at');
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeDueToday($query)
    {
        return $query->whereNull('completed_at')->whereNotNull('due_at')->whereDate('due_at', '=', now(session()->get('timezone'))->toDateString());
    }


    /** Accessors and Mutators */

    public function getIsOverdueAttribute()
    {
        return empty($this->completed_at) && !empty($this->due_at) && $this->due_at <= now(session()->get('timezone'));
    }

    // at what pint today is respect created_at and due_at

    public function getPercentInTimeAttribute()
    {
        $completionPeriod = $this->due_at->copy()->diffInDays($this->created_at);
        $timePassed = $this->created_at->copy()->diffInDays(now(session()->get('timezone')));

        return !empty($completionPeriod) ? round($timePassed * 100 / $completionPeriod, 1) : null;
    }

    public function getDaysLeftAttribute()
    {
        $completionPeriod = $this->due_at->copy()->diffInDays($this->created_at);
        $timePassed = $this->created_at->copy()->diffInDays(now(session()->get('timezone')));

        return $completionPeriod > $timePassed ? $completionPeriod - $timePassed : 0;
    }

    public function getHtmlDueAtAttribute()
    {
        return !empty($this->due_at) ? $this->due_at->format('M. d, Y') . '<br>' . $this->due_at->format('g:i A') : null;
    }

    public function getHtmlCompletedAtAttribute()
    {
        return !empty($this->completed_at) ? $this->completed_at->format('M. d, Y') . '<br>' . $this->completed_at->format('g:i A') : null;
    }


    /** Methods */


}
