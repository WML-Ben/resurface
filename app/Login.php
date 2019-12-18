<?php namespace App;

use SortableTrait;
use SearchTrait;

class Login extends BaseModel
{

    use SortableTrait, SearchTrait;

    public $dates = ['logged_in', 'logged_out'];

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'session_id',
        'logged_in',
        'logged_out',
    ];

    public $sortable = [
        'user_id',
        'logged_in',
        'logged_out',
        'ip_address',
        'logins.user_id|users.first_name',
    ];

    public $searchable = [
        'ip_address' => 'LIKE',
        'user_agent' => 'LIKE',
        'logged_in'  => 'LIKE',
        'logged_out' => 'LIKE',

        'childModel' => [
            'modelName' => 'User',
            'fields' => [
                'first_name' => 'LIKE',
                'last_name'  => 'LIKE',
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

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /** scopes */

    public function scopeOrdered($query, $amount = null)
    {
        $query->orderBy('logged_in', 'DESC');
        if ( ! empty($amount)) {
            $query->take($amount);
        }

        return $query;
    }

    public function scopeNoRoot($query, $amount = null)
    {
        $query->where('user_id', '>', 1);
        if ( ! empty($amount)) {
            $query->take($amount);
        }

        return $query;
    }

}
