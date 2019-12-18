<?php namespace App;

use App\Scopes\SubContractorScope;

class SubContractor extends User
{
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        self::addGlobalScope(new SubContractorScope);
    }

    /** relationships */


    /** scopes */


    /** Methods */

    static public function subContractorsCB($default = [])
    {
        $items = self::active()
            ->get()
            ->pluck('full_name', 'id')
            ->toArray();

        if (!empty($default)) {
            return self::mergeAssoc($default, $items);
        }

        return $items;
    }

    static public function jsonHtmlSubContractorsCB($defaultEmpty = true)
    {
        $subContractors = self::orderBy('first_name')->get();

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

        foreach ($subContractors as $subContractor) {
            $items[] = [
                'id'    => $subContractor->id,
                'text'  => $subContractor->fullName,
                'html'  => '<div class="select2-option-html"><div class="single-line">' . $subContractor->fullName . '</div></div>',
                'title' => '',
            ];
        }

        return json_encode($items);
    }


}
