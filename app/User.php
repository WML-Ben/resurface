<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use XuacTrait;

use SortableTrait, SearchTrait;

class User extends Authenticatable
{
    use Notifiable, XuacTrait, SortableTrait, SearchTrait;

    public $dates = ['date_of_birth', 'hired_at'];

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'salutation',
        'email',
        'password',
        'role_id',
        'category_id',
        'company_id',
        'avatar',
        'signature',
        'title',
        'date_of_birth',
        'phone',
        'alt_phone',
        'alt_email',
        'address',
        'address_2',
        'city',
        'zipcode',
        'state_id',
        'country_id',
        'overhead',
        'qualified',
        'is_employee',
        'hired_at',
        'comment',
        'disabled',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'disabled'];

    /** Sortable needed for sorted queries. Against this array is check run, if column name is not in array it will not work */

    public $sortable = [
        'first_name',
        'last_name',
        'email',
        'disabled',
        'users.role_id|roles.name',
        'users.category_id|user_categories.name',
        'users.company_id|companies.name',
        'users.company_position_id|company_positions.name',
    ];

    public $searchable = [
        'first_name' => 'LIKE',
        'last_name'  => 'LIKE',
        'email'      => 'LIKE',
        'childModels'   => [
            'role' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'category' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'company' => [
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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function logins()
    {
        return $this->hasMany(Login::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'id', 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'id', 'country_id');
    }

    public function category()
    {
        return $this->belongsTo(UserCategory::class, 'category_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /** Scopes */

    public function scopeNoRoot($query)
    {
        return $query->where('role_id', '!=', 1);
    }

    public function scopeActive($query)
    {
        return $query->where($this->getTable().'.disabled', 0);
    }

    public function scopeShowAllFilter($query, $showAll = 0)
    {
        return !empty($showAll) ? $query : $query->active();
    }

    /** Mutators and Accessors */

    public function getFullNameAttribute()
    {
        return trim($this->first_name .' '. trim($this->middle_name .' '. $this->last_name));
    }

    public function getOwnRoleAttribute()
    {
        return $this->role->name ?? null;
    }

    public function getShortLocationAttribute()
    {
        return $this->buildAddress($this->address ?? null, $this->city ?? null, $this->state->name ?? null, $this->zipcode ?? null, null, '<br>');
    }

    // Xuac related:

    // Roles:

    public function getRolesAttribute()
    {
        if (!empty($this->reload_credentials)) {
            $credentials = $this->fetchCredentials();

            session()->put('credentials', $credentials);

            $this->resetResetCredentials();
        }
        if (!$credentials = session()->get('credentials')) {
            $credentials = $this->fetchCredentials();

            session()->put('credentials', $credentials);

            $this->resetResetCredentials();
        }

        return $credentials->roles;
    }

    // Privileges:

    public function getPrivilegesAttribute()
    {
        if (!empty($this->reload_credentials)) {
            $credentials = $this->fetchCredentials();

            session()->put('credentials', $credentials);

            $this->resetResetCredentials();
        }
        if (!$credentials = session()->get('credentials')) {
            $credentials = $this->fetchCredentials();

            session()->put('credentials', $credentials);

            $this->resetResetCredentials();
        }

        return $credentials->privileges;
    }
    // ------------------------

    public function getHtmlFullNameAndContactInfoAttribute()
    {
        $arr = [];

        if (!empty($this->fullName)) {
            $arr[] = '<span class="info-first-row">'. $this->fullName .'</span>';
        }
        if (!empty($this->email)) {
            $arr[] = '<span class="info-other-rows">'. $this->email .'</span>';
        }
        if (!empty($this->phone)) {
            $arr[] = '<span class="info-other-rows">'. $this->phone .'</span>';
        }

        return !empty($arr) ? implode('<br>', $arr) : null;
    }

    /** Methods */

    public function fetchCredentials()
    {
        $credentials = [
            'roles'      => $this->rolesOwnAndBelow($this->role_id),
            'privileges' => $this->authUserPrivileges(),
        ];

        return (object)$credentials;
    }

    public function setResetCredentials()
    {
        $this->reload_credentials = 1;
        $this->save();
    }

    public function resetResetCredentials()
    {
        $this->reload_credentials = 0;
        $this->save();
    }

    public static function setAllResetCredentials()
    {
        self::where('reload_credentials', 0)->update(['reload_credentials' => 1]);
    }

    // Xuac related:

    // Roles:

    public function hasRoles($roles = [])
    {
        return (boolean)(count(array_intersect($this->roles, (array)$roles)));
    }

    // Privileges:

    public function hasPrivileges($privileges = [])
    {
        return (boolean)(count(array_intersect($this->privileges, (array)$privileges)));
    }

    // alias functions:

    // Roles:

    public function hasRole($role)
    {
        return $this->hasRoles($role);
    }

    // Privileges:

    public function isAllowTo($privileges)
    {
        return $this->hasPrivileges($privileges);
    }

    public function isNotAllowTo($privilege)
    {
        return ! $this->hasPrivileges($privilege);
    }

    public function hasPrivilege($privilege)
    {
        return $this->hasPrivileges($privilege);
    }

    // others

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordLink($token));
    }

    static public function mergeAssoc($arr1, $arr2)
    {
        if (!is_array($arr1)) {
            $arr1 = [];
        }
        if (!is_array($arr2)) {
            $arr2 = [];
        }

        $keys1 = array_keys($arr1);
        $keys2 = array_keys($arr2);
        $keys = array_merge($keys1, $keys2);
        $vals1 = array_values($arr1);
        $vals2 = array_values($arr2);
        $vals = array_merge($vals1, $vals2);
        $ret = [];

        foreach ($keys as $key) {
            //list(, $val) = each($vals);
            //$ret[$key] = $val;
        }

        return $ret;
    }
    
}