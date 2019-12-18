<?php namespace App;

use App\Scopes\ProposalScope;

class Proposal extends Order
{
    protected $table = 'orders';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ProposalScope);
    }

    public function __construct()
    {
        parent::__construct();

        $this->fillable[] = 'start_id';

        $this->sortable[] = 'orders.start_id|order_starts.name';

        $this->searchable['childModels']['start'] = [
            'fields' => [
                'name' => 'LIKE',
            ],
        ];
    }

    /** relationships */

    public function start()
    {
        return $this->belongsTo(OrderStart::class, 'start_id');
    }

    /** scopes */

    public function scopeProposalDraft($query)
    {
        return $query->where('status_id', 10);
    }

    public function scopeProposalPending($query)
    {
        return $query->where('status_id', 1);
    }

    public function scopeProposalActive($query)
    {
        return $query->where('status_id', 1);
    }

    public function scopeProposalApproved($query)
    {
        return $query->where('status_id', 2);
    }

    public function scopeProposalRejected($query)
    {
        return $query->where('status_id', 3);
    }

    public function scopeProposalSigned($query)
    {
        return $query->where('status_id', 4);
    }

    public function scopeAgingFilter($query, $agingId = null)
    {
        if (empty($agingId) || !($agePeriod = \App\AgePeriod::find($agingId))) {
            return $query;
        }

        switch ($agingId) {
            case '1':
                return $query->where('created_at', '>=', now(session()->get('timezone'))->startOfDay()->subDays($agePeriod->final_day));
                break;
            case '2':
                return $query->whereBetween('created_at', [now(session()->get('timezone'))->startOfDay()->subDays($agePeriod->final_day), now(session()->get('timezone'))->endOfDay()->subDays($agePeriod->initial_day)]);
                break;
            case '3':
                return $query->where('created_at', '<=', now(session()->get('timezone'))->startOfDay()->endOfDay($agePeriod->initial_day));
                break;
            default:
                return $query;
                break;

        }
    }

    public function scopeStartFilter($query, $startId = null)
    {
        if (empty($startId)) {
            return $query;
        }

        return $query->where('start_id', $startId);
    }

    public function scopeAgePeriodActive($query, $initiaDay, $finalDay)
    {
        $query->proposalActive();

        if (empty($initiaDay) && empty($finalDay)) {
            return $query;
        }

        if (empty($initiaDay)) {
            // upto $finalDay:

            return $query->where('created_at', '>=', now(session()->get('timezone'))->startOfDay()->subDays($finalDay));
        } else if (empty($finalDay)) {
            // no less than $initiaDay

            return $query->where('created_at', '<=', now(session()->get('timezone'))->endOfDay()->subDays($initiaDay));
        } else {
            // between $initiaDay and $finalDay:

            return $query->whereBetween('created_at', [now(session()->get('timezone'))->startOfDay()->subDays($finalDay), now(session()->get('timezone'))->endOfDay()->subDays($initiaDay)]);
        }
    }

    public function getAgedPeriodAttribute()
    {
        return !empty($this->created_at) ? \App\AgePeriod::getAgePeriodFromDate($this->created_at) : null;
    }

    public function getAgedCreatedAtAttribute()
    {
        $html = '';

        if (!empty($this->created_at)) {
            $agePeriod = \App\AgePeriod::getAgePeriodFromDate($this->created_at);

            $html = '<div><i class="'. $agePeriod->icon_class .'"'. (!empty($agePeriod->icon_color) ? ' style="color:'. $agePeriod->icon_color .'"' : '') .' data-toggle="tooltip" title="'. $agePeriod->name .'"></i><br>'. $this->created_at->format('M, n, Y') .'</div>';
        }

        return $html;
    }

    public function getAgedIconAttribute()
    {
        $html = '';

        if (!empty($this->created_at)) {
            $agePeriod = \App\AgePeriod::getAgePeriodFromDate($this->created_at);

            $html = '<div><i class="'. $agePeriod->icon_class .'"'. (!empty($agePeriod->icon_color) ? ' style="color:'. $agePeriod->icon_color .'"' : '') .' data-toggle="tooltip" title="'. $agePeriod->name .'"></i></div>';
        }

        return $html;
    }


    /** Ancestors and Mutators */



    /** Methods */

    public static function getPeriodActiveCount($initiaDay, $finalDay)
    {
        return self::agePeriodActive($initiaDay, $finalDay)->count();
    }


}
