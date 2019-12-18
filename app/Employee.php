<?php namespace App;

use App\Scopes\EmployeeScope;

class Employee extends User
{
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            if (!empty($model->password)) {
                $model->password = \Hash::make($model->password);
            }

            $model->created_at = now(session()->get('timezone'));
            $model->created_by = auth()->user()->id;
        });

        self::updating(function($model) {
            $model->updated_at = now(session()->get('timezone'));
            $model->updated_by = auth()->user()->id;
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
            $model->is_employee = true;
            $model->company_id = 1;              // All Paving
            $model->company_position_id = 1;     // always and all will be elmployees, as mike said.
            $model->category_id = 18;            // General Contact
        });

        self::addGlobalScope(new EmployeeScope);
    }

    /** relationships */

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user', 'user_id', 'company_id');
    }

    
    /** scopes */


    /** Methods */

    static public function employeesCB($default = [], $roleIds = [])
    {
        $query = self::active();

        if (!empty($roleIds)) {
            $query->whereIn('role_id', $roleIds);
        }

        $items = $query
            ->get()
            ->pluck('full_name', 'id')
            ->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function jobSiteManagersCB($default = [])
    {
        return self::employeesCB($default, ['6']);
    }

    static public function salesManagersCB($default = [])
    {
        return self::employeesCB($default, ['7', '3']);
    }

    static public function salesPersonsCB($default = [])
    {
        return self::employeesCB($default, ['7', '8', '3']);
    }

    static public function jsonHtmlEmployeesCB($defaultEmpty = true)
    {
        $employees = self::orderBy('first_name')->get();

        $items = [];

        if (!empty($defaultEmpty)) {
            $items[] = [
                'id'    => 0,
                'text'  => '',
                'html'  => '',
                'title' => '',
            ];
        }

        /**
         *  - Search while typing will be condiucted on "text" content.
         *  - After selecting option, "text" content will be shown (can be html for styling).
         *  - Options are the "html" content
         *  - "title" will be shown when hovering the option
         */

        foreach ($employees as $employee) {
            $items[] = [
                'id'    => $employee->id,
                'text'  => $employee->fullName,
                'html'  => '<div class="select2-option-html"><div class="single-line">' . $employee->fullName . '</div></div>',
                'title' => '',
            ];
        }

        return json_encode($items);
    }


}
