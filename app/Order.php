<?php namespace App;

use App\Scopes\OrderScope;

use SortableTrait;
use SearchTrait;

class Order extends BaseModel
{
    use SortableTrait, SearchTrait;

    public $dates = ['notice_to_owner_sent_at', 'modification_of_traffic_sent_at'];

    protected $fillable = [
        'company_id',
        'status_id',
        'property_id',
        'manager_id',
        'sales_manager_id',
        'sales_person_id',
        'start_id',
        'order_number',
        'name',
        'email',
        'phone',
        'alt_phone',
        'contact',
        'address',
        'address_2',
        'city',
        'zipcode',
        'state_id',
        'country_id',
        'parcel_number',
        'billing_address',
        'billing_address_2',
        'billing_city',
        'billing_zipcode',
        'billing_state_id',
        'billing_country_id',
        'discount',
        'alert',
        'alert_by',
        'alert_note',
        'notice_to_owner',
        'notice_to_owner_sent_at',
        'notice_to_owner_sent_by',
        'permit_required',
        'mot',
        'modification_of_traffic_sent_at',
        'modification_of_traffic_sent_by',
        'invoice_number',
        'invoice_amount',
        'what_services_were_you_looking_for',
        'how_did_you_hear_about_us',
        'referring_person',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public $sortable = [
        'name',
        'created_at',
        'order_number',
        'orders.status_id|order_status.name',
        'orders.company_id|companies.name',
        'orders.property_id|properties.name',
        'orders.manager_id|users.first_name',
        'orders.sales_manager_id|users.first_name',
        'orders.sales_person_id|users.first_name',
        'orders.created_by|users.first_name',
        'orders.updated_by|users.first_name',
    ];

    public $searchable = [
        'name' => 'LIKE',
        'childModels' => [
            'status'      => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'property'      => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'company'      => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'manager' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'salesManager' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'salesPerson' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
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

        self::creating(function($model) {
            $model->created_at = now(session()->get('timezone'));
            $model->created_by = auth()->user()->id;
        });

        self::saving(function($model) {
            if (empty($model->disabled)) {
                $model->disabled = 0;
            }
            if (empty($model->state_id)) {
                $model->state_id = 3930;
            }
            if (empty($model->country_id)) {
                $model->country_id = 231;
            }
            if (!empty($model->above_as_billing_address)) {
                $model->billing_address = $model->address;
                $model->billing_address_2 = $model->address_2;
                $model->billing_city = $model->city;
                $model->billing_zipcode = $model->zipcode;
                $model->billing_state_id = $model->state_id;
                $model->billing_country_id = $model->country_id;
            }

            $model->updated_at = now(session()->get('timezone'));
            $model->updated_by = auth()->user()->id;
        });

        self::addGlobalScope(new OrderScope);
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

    public function statusHistory()
    {
        return $this->hasMany(OrderHistory::class, 'order_id');
    }

    public function services()
    {
        return $this->hasMany(OrderService::class, 'order_id');
    }

    public function pendingServices()
    {
        return $this->hasMany(OrderService::class, 'order_id')->where('status_id', 5);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function salesManager()
    {
        return $this->belongsTo(User::class, 'sales_manager_id');
    }

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    public function alertBy()
    {
        return $this->belongsTo(User::class, 'alert_by');
    }

    public function noticeToOwnerSentBy()
    {
        return $this->belongsTo(User::class, 'notice_to_owner_sent_by');
    }

    public function motSentBy()
    {
        return $this->belongsTo(User::class, 'modification_of_traffic_sent_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function billingState()
    {
        return $this->belongsTo(State::class, 'billing_state_id');
    }

    public function billingCountry()
    {
        return $this->belongsTo(Country::class, 'billing_country_id');
    }

    public function permit()
    {
        return $this->hasMany(Permit::class);
    }

    public function notes()
    {
        return $this->hasMany(OrderNote::class, 'order_id');
    }

    public function materials()
    {
        return $this->hasMany(OrderMaterial::class, 'order_id');
    }

    public function media()
    {
        return $this->hasMany(OrderMedia::class, 'order_id');
    }

    /** scopes */

    public function scopeWorkOrderProcessing($query)   // to be used oon OrderService
    {
        return $query->where('status_id', '=', 9);
    }

    public function scopeWorkOrderActive($query)   // to be used oon OrderService
    {
        return $query->where('status_id', '=', 5);
    }

    public function scopeWorkOrderBilling($query)   // to be used oon OrderService
    {
        return $query->where('status_id', '=', 6);
    }

    public function scopeStatusFilter($query, $statusId = null)
    {
        if (empty($statusId)) {
            return $query;
        }

        return $query->where('status_id', $statusId);
    }

    public function scopeBasedOnRole($query)
    {
        if (auth()->user()->hasRoles(['admin', 'office_worker'])) {
            return $query;                                              // can see all
        } else if (auth()->user()->hasRoles(['sales_person'])) {
            return $query->where(function($q){
                $q->orWhere('sales_manager_id', auth()->user()->id)
                    ->orWhere('sales_person_id', auth()->user()->id)
                    ->orWhere('created_by', auth()->user()->id);
            });
        }

        return null;
    }


    /** Accessors and Mutators */

    public function getHoldAsPermitIsRequiredAttribute()
    {
        return !empty($this->permit->permit_status_id) && !empty($this->permit->permit_status_id) && $this->permit->is_not_approved;
    }

    public function getServicesTotalCostAttribute()
    {
        return $this->services()->sum('cost');
    }

    public function getServicesTotalCostCurrencyAttribute()
    {
        $servicesTotalCost = $this->services()->sum('cost');

        return is_numeric($servicesTotalCost) ? '$' . sprintf('%s', number_format($servicesTotalCost / 100, 2)) : null;
    }

    public function getServicesTotalCostWithDiscountAttribute()
    {
        $servicesTotalCost = $this->services()->sum('cost');

        if (!empty($this->discount)) {
            $servicesTotalCost *= 1 - $this->discount / 100;
        }

        return $servicesTotalCost;
    }

    public function getServicesTotalCostWithDiscountCurrencyAttribute()
    {
        $servicesTotalCost = $this->services()->sum('cost');

        if (!empty($this->discount)) {
            $servicesTotalCost *= 1 - $this->discount / 100;
        }

        return is_numeric($servicesTotalCost) ? '$' . sprintf('%s', number_format($servicesTotalCost / 100, 2)) : null;
    }

    /** Methods */

    public function materialsAssoc()
    {
        $arr = [];
        foreach ($this->materials as $material) {
            $arr[$material->material_id] = $material;
        }

        return $arr;
    }

    public function generateWorkOrderNumber($date = null)
    {
        $orderNumber = \App\OrderNumber::create();

        $d = $date ?? now(session()->get('timezone'));

        return $d->year . '-' . str_pad($d->month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($orderNumber->id, 5, '0', STR_PAD_LEFT);
    }


}
