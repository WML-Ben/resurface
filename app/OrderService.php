<?php namespace App;

use SortableTrait;
use SearchTrait;

class OrderService extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $dates = ['started_at', 'ended_at', 'completed_at'];

    protected $fillable = [
        'order_id',
        'order_service_status_id',
        'service_id',
        'manager_id',
        'sub_manager_id',
        'vendor_id',
        'striping_vendor_id',
        'name',
        'description',
        'address',
        'address_2',
        'city',
        'zipcode',
        'state_id',
        'country_id',
        'parcel_number',
        'loads',
        'locations',
        'linear_feet',
        'cost_per_linear_feet',
        'square_feet',
        'square_yards',
        'cubic_yards',
        'tons',
        'depth_in_inches',
        'days',
        'cost_per_day',
        'break_even',
        'yield',
        'primer',
        'fast_set',
        'sand',
        'additive',
        'sealer',
        'phases',
        'overhead',
        'cost',
        'profit',
        'proposal_text',
        'note',
        'started_at',
        'ended_at',
        'd_sort',
        'scheduled_by',
        'completed_at',
        'completed_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'name',
        'd_sort',
        'order_services.order_id|orders.order_number',
        'order_services.order_service_status_id|order_service_status.name',
        'order_services.service_id|services.name',
        'order_services.manager_id|users.first_name',
        'order_services.sub_manager_id|users.first_name',
        'order_services.order_id|orders-id-property_id|properties.name',
        'order_services.order_id|orders-id-company_id|companies.name',
    ];

    public $searchable = [
        'name'        => 'LIKE',
        'childModels' => [
            'order'              => [
                'fields' => [
                    'order_number' => 'LIKE',
                ],
            ],
            'orderServiceStatus' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'manager'            => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'subManager'         => [
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

        self::creating(function($model) {
            $model->created_at = now(session()->get('timezone'));
            $model->created_by = auth()->user()->id;

            $model->d_sort = $model->getMaxDSort() + 1;
        });

        self::updating(function($model) {
            $model->updated_at = now(session()->get('timezone'));
            $model->updated_by = auth()->user()->id;
        });

        self::saving(function($model) {
            if (empty($model->state_id)) {
                $model->state_id = 3930;
            }
            if (empty($model->country_id)) {
                $model->country_id = 231;
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

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->whereNotNull('property_id');
    }

    public function orderServiceStatus()
    {
        return $this->belongsTo(OrderServiceStatus::class, 'order_service_status_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function serviceCategory()
    {
        return $this->hasManyThrough(ServiceCategory::class, Service::class,'id', 'id', 'service_id', 'service_category_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function subManager()
    {
        return $this->belongsTo(User::class, 'sub_manager_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function stripingVendor()
    {
        return $this->belongsTo(StripingVendor::class, 'striping_vendor_id');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function scheduledBy()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function permit()
    {
        return $this->hasOne(Permit::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function checklist()
    {
        return $this->hasOne(OrderServiceChecklist::class, 'order_service_id');
    }

    public function equipments()
    {
        return $this->hasMany(OrderServiceEquipment::class, 'order_service_id');
    }

    public function labors()
    {
        return $this->hasMany(OrderServiceLabor::class, 'order_service_id');
    }

    public function materials()
    {
        return $this->hasMany(OrderServiceMaterial::class, 'order_service_id');
    }

    public function otherCosts()
    {
        return $this->hasMany(OrderServiceOtherCost::class, 'order_service_id');
    }

    public function subContractor()
    {
        return $this->hasOne(SubContractor::class, 'vendor_id');
    }

    public function subContactors()
    {
        return $this->hasMany(OrderServiceSubContractor::class, 'order_service_id');
    }

    public function vehicleTypes()
    {
        return $this->hasMany(OrderServiceVehicleType::class, 'order_service_id');
    }

    public function stripingVendorServices()
    {
        return $this->hasMany(OrderServiceStripingVendorService::class, 'order_service_id');
    }

    /** scopes */

    public function scopeStatusFilter($query, $statusId = null)
    {
        if (empty($statusId)) {
            return $query;
        }

        if ($statusId == '1000') {
            return $query->whereIn('order_service_status_id', [3, 4]);
        }

        return $query->where('order_service_status_id', $statusId);
    }

    public function scopeWithWorkOrderActive($query)
    {
        return $query->whereHas('order', function ($q) {
            $q->workOrderActive();
        });
    }

    public function scopeOrderServiceActive($query)
    {
        return $query->whereIn('order_service_status_id', [3, 4]);
    }

    public function scopeOrderServiceCanceled($query)
    {
        return $query->where('order_service_status_id', 1);
    }

    public function scopeOrderServiceCompleted($query)
    {
        return $query->where('order_service_status_id', 2);
    }

    public function scopeOrderServiceNoScheduled($query)
    {
        return $query->where('order_service_status_id', 3);
    }

    public function scopeOrderServiceScheduled($query)
    {
        return $query->where('order_service_status_id', 4);
    }

    /** Accessors and Mutators */

    public function getCategoryAttribute()
    {
        return $this->serviceCategory()->first();
    }

    public function getScheduleAttribute()
    {
        return '<div>Start: ' . (!empty($this->started_at) ? $this->started_at->format('M. d, Y') : 'unknown') . '<br>End: ' . (!empty($this->ended_at) ? $this->ended_at->format('M. d, Y') : 'unknown') . '</div>';
    }

    public function getLinearFeetAttribute()
    {
        return is_numeric($this->attributes['linear_feet']) ? $this->attributes['linear_feet'] / 100 : 0;
    }

    public function setLinearFeetAttribute($value)
    {
        $this->attributes['linear_feet'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getLinearFeetCurrencyAttribute()
    {
        return is_numeric($this->attributes['linear_feet']) ? '$' . sprintf('%s', number_format($this->attributes['linear_feet'] / 100, 2)) : null;
    }

    public function getCostPerLinearFeetAttribute()
    {
        return is_numeric($this->attributes['cost_per_linear_feet']) ? $this->attributes['cost_per_linear_feet'] / 100 : 0;
    }

    public function setCostPerLinearFeetAttribute($value)
    {
        $this->attributes['cost_per_linear_feet'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getCostPerLinearFeetCurrencyAttribute()
    {
        return is_numeric($this->attributes['cost_per_linear_feet']) ? '$' . sprintf('%s', number_format($this->attributes['cost_per_linear_feet'] / 100, 2)) : null;
    }

    public function getSquareFeetAttribute()
    {
        return is_numeric($this->attributes['square_feet']) ? $this->attributes['square_feet'] / 100 : 0;
    }

    public function setSquareFeetAttribute($value)
    {
        $this->attributes['square_feet'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getSquareFeetCurrencyAttribute()
    {
        return is_numeric($this->attributes['square_feet']) ? '$' . sprintf('%s', number_format($this->attributes['square_feet'] / 100, 2)) : null;
    }

    public function getSquareYardAttribute()
    {
        return is_numeric($this->attributes['square_yard']) ? $this->attributes['square_yard'] / 100 : 0;
    }

    public function setSquareYardAttribute($value)
    {
        $this->attributes['square_yard'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getSquareYardCurrencyAttribute()
    {
        return is_numeric($this->attributes['square_yard']) ? '$' . sprintf('%s', number_format($this->attributes['square_yard'] / 100, 2)) : null;
    }

    public function getCubicYardAttribute()
    {
        return is_numeric($this->attributes['cubic_yard']) ? $this->attributes['cubic_yard'] / 100 : 0;
    }

    public function setCubicYardAttribute($value)
    {
        $this->attributes['cubic_yard'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getCubicYardCurrencyAttribute()
    {
        return is_numeric($this->attributes['cubic_yard']) ? '$' . sprintf('%s', number_format($this->attributes['cubic_yard'] / 100, 2)) : null;
    }

    public function getTonsAttribute()
    {
        return is_numeric($this->attributes['tons']) ? $this->attributes['tons'] / 100 : 0;
    }

    public function setTonsAttribute($value)
    {
        $this->attributes['tons'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getTonsCurrencyAttribute()
    {
        return is_numeric($this->attributes['tons']) ? '$' . sprintf('%s', number_format($this->attributes['tons'] / 100, 2)) : null;
    }

    public function getDepthInInchesAttribute()
    {
        return is_numeric($this->attributes['depth_in_inches']) ? $this->attributes['depth_in_inches'] / 100 : 0;
    }

    public function setDepthInInchesAttribute($value)
    {
        $this->attributes['depth_in_inches'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getDepthInInchesCurrencyAttribute()
    {
        return is_numeric($this->attributes['depth_in_inches']) ? '$' . sprintf('%s', number_format($this->attributes['depth_in_inches'] / 100, 2)) : null;
    }

    public function getCostPerDayAttribute()
    {
        return is_numeric($this->attributes['cost_per_day']) ? $this->attributes['cost_per_day'] / 100 : 0;
    }

    public function setCostPerDayAttribute($value)
    {
        $this->attributes['cost_per_day'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getCostPerDayCurrencyAttribute()
    {
        return is_numeric($this->attributes['cost_per_day']) ? '$' . sprintf('%s', number_format($this->attributes['cost_per_day'] / 100, 2)) : null;
    }

    public function getBreakEvenAttribute()
    {
        return is_numeric($this->attributes['break_even']) ? $this->attributes['break_even'] / 100 : 0;
    }

    public function setBreakEvenAttribute($value)
    {
        $this->attributes['break_even'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getBreakEvenCurrencyAttribute()
    {
        return is_numeric($this->attributes['break_even']) ? '$' . sprintf('%s', number_format($this->attributes['break_even'] / 100, 2)) : null;
    }

    public function getPrimerAttribute()
    {
        return is_numeric($this->attributes['primer']) ? $this->attributes['primer'] / 100 : 0;
    }

    public function setPrimerAttribute($value)
    {
        $this->attributes['primer'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getPrimerCurrencyAttribute()
    {
        return is_numeric($this->attributes['primer']) ? '$' . sprintf('%s', number_format($this->attributes['primer'] / 100, 2)) : null;
    }

    public function getFastSetAttribute()
    {
        return is_numeric($this->attributes['fast_set']) ? $this->attributes['fast_set'] / 100 : 0;
    }

    public function setFastSetAttribute($value)
    {
        $this->attributes['fast_set'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getFastSetCurrencyAttribute()
    {
        return is_numeric($this->attributes['fast_set']) ? '$' . sprintf('%s', number_format($this->attributes['fast_set'] / 100, 2)) : null;
    }

    public function getAdditiveAttribute()
    {
        return is_numeric($this->attributes['additive']) ? $this->attributes['additive'] / 100 : 0;
    }

    public function setAdditiveAttribute($value)
    {
        $this->attributes['additive'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getAdditiveCurrencyAttribute()
    {
        return is_numeric($this->attributes['additive']) ? '$' . sprintf('%s', number_format($this->attributes['additive'] / 100, 2)) : null;
    }

    public function getOverheadAttribute()
    {
        return is_numeric($this->attributes['overhead']) ? $this->attributes['overhead'] / 100 : 0;
    }

    public function setOverheadAttribute($value)
    {
        $this->attributes['overhead'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getOverheadCurrencyAttribute()
    {
        return is_numeric($this->attributes['overhead']) ? '$' . sprintf('%s', number_format($this->attributes['overhead'] / 100, 2)) : null;
    }

    public function getCostAttribute()
    {
        return is_numeric($this->attributes['cost']) ? $this->attributes['cost'] / 100 : 0;
    }

    public function setCostAttribute($value)
    {
        $this->attributes['cost'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getCostCurrencyAttribute()
    {
        return is_numeric($this->attributes['cost']) ? '$' . sprintf('%s', number_format($this->attributes['cost'] / 100, 2)) : null;
    }

    public function getProfitAttribute()
    {
        return is_numeric($this->attributes['profit']) ? $this->attributes['profit'] / 100 : 0;
    }

    public function setProfitAttribute($value)
    {
        $this->attributes['profit'] = is_numeric($value) ? intval($value * 100) : 0;
    }

    public function getProfitCurrencyAttribute()
    {
        return is_numeric($this->attributes['profit']) ? '$' . sprintf('%s', number_format($this->attributes['profit'] / 100, 2)) : null;
    }


    /** Methods */

    public function getServiceCategoryBlade()
    {
        // from services table   from line 227 on poAddServices.tpl

        switch ($this->service->service_category_id) {
            case '1':                                   // Asphalt
                switch ($this->service_id) {
                    case '19':
                        $blade = 'asphalt_19';
                        break;
                    case '3':
                    case '4':
                    case '5':
                    case '22':
                        $blade = 'asphalt_3_4_5_22';
                        break;
                }
                break;
            case '2':                                   // Concrete
                if ($this->service_id < 12) {
                    $blade = 'concrete_lt_12';
                } else {
                    $blade = 'concrete_egt_12';
                }
                break;
            case '3':                                   // Drainage and Catchbasins
                $blade = 'drainage_and_catchbasins';
                break;
            case '4':                                   // Excavation
                $blade = 'excavation';
                break;
            case '5':                                   // Other
                $blade = 'other';
                break;
            case '6':                                   // Paver Brick
                $blade = 'paver_brick';
                break;
            case '7':                                   // Rock
                $blade = 'rock';
                break;
            case '8':                                   // Seal Coating
                $blade = 'sealcoating';
                break;
            case '9':                                   // Striping
                $blade = 'striping';
                break;
            case '10':                                  // Sub Contractor
                $blade = 'sub_contractor';
                break;
        }

        return $blade;
    }

    public function getMaxDSort()
    {
        return $this->where('order_id', $this->order_id)->where('d_sort', '<', 1000)->max('d_sort');
    }

    public function checklistCB($default = [])
    {
        $fields = [
            'trencher',
            'hand_saw',
            'plate_compactor',
            'shovel',
            'pick',
            'forms_pins',
            'roller',
            'street_saw',
            'hand_finishing_tools',
            'orange_paint',
            'gasoline',
            'rake',
            'tack',
            'blower',
            'wands',
            'brush_broom',
            'string_line',
            'barricades',
            'check_tanker',
            'check_tires',
        ];

        if (empty($this->checklist)) {
            return !empty($default) ? $default : null;
        }

        $items = array_only($this->checklist->toArray(), $fields);

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

}
