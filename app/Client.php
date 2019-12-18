<?php namespace App;

use App\Scopes\ClientScope;

class Client extends User
{
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ClientScope);
    }

    /** relationships */

    
    /** scopes */


    /** Methods */

    static public function clientsCB($default = [])
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


}
