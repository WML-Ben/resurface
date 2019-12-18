<?php namespace App;

use App\Scopes\ContactScope;

class Contact extends User
{
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        self::creating(function($model) {
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
            if (empty($model->category_id)) {
                $model->category_id = 18;        // General Contact
            }
            if (empty($model->company_id)) {
                $model->company_id = null;
            }
            if (empty($model->qualified)) {
                $model->qualified = 0;
            }
            if (empty($model->state_id)) {
                $model->state_id = 3930;
            }
            if (empty($model->country_id)) {
                $model->country_id = 231;
            }
            $model->is_employee = false;
            $model->role_id = null;

            \Cache::forget('json_html_contacts_cb');

            if ($model->category_id == 16) {
                \Cache::forget('json_html_property_owners_cb');
            }
        });

        self::addGlobalScope(new ContactScope);
    }

    /** relationships */

    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user', 'user_id', 'company_id');
    }
    
    /** scopes */


    /** Methods    UPDATE  */

    static public function contactsCB($default = [], $roleIds = [])
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

    static public function salesManagersCB($default = [])
    {
        return self::contactsCB($default, ['7']);
    }

    static public function salesPersonsCB($default = [])
    {
        return self::contactsCB($default, ['8']);
    }

    static public function jsonHtmlContactsCB($defaultEmpty = true)
    {
        $contacts = self::orderBy('first_name')->get();

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
         *  - Search while typing will be conducted on "text" content.
         *  - After selecting option, "text" content will be shown (can be html for styling).
         *  - Options are the "html" content
         *  - "title" will be shown when hovering the option
         */

        foreach ($contacts as $contact) {
            $secondLine = [];

            if (!empty($contact->email)) {
                $secondLine[] = $contact->email;
            }
            if (!empty($contact->phone)) {
                $secondLine[] = $contact->phone;
            }

            $secondLine = !empty($secondLine) ? '<div class="second-line">'. implode('<br>', $secondLine) .'</div>' : '';

            $items[] = [
                'id'    => $contact->id,
                'text'  => $contact->fullName,
                'html'  => '<div class="select2-option-html"><div class="single-line">' . $contact->fullName . '</div>'. ($secondLine ?? '') .'</div>',
                'title' => '',
            ];
        }

        return json_encode($items);
    }


}
