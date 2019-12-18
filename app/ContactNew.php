<?php namespace App;

use SortableTrait;
use SearchTrait;

class ContactNew extends BaseModel
{
    protected $table = 'contacts';
	
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
		'category_id',
		'company_id',
        'phone',
        'address',
        'city',
		'zipcode',
		'state_id',
		'country_id',
        'created_at',
		'updated_at',
		'created_by',
		'updated_by',
		'is_delete'
    ];
	public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user', 'user_id', 'company_id');
    }
    

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
                'text'  => $contact->first_name.' '.$contact->last_name,
                'html'  => '<div class="select2-option-html"><div class="single-line">' . $contact->first_name.' '.$contact->last_name . '</div>'. ($secondLine ?? '') .'</div>',
                'title' => '',
            ];
        }

        return json_encode($items);
    }
}
