<?php namespace App;


class RoleChildren extends BaseModel
{

    protected $table = 'role_children';

    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'child_id',
    ];

    /** relationships */

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function privileges()
    {
        return $this->belongsToMany(Privilege::class);
    }

    public function parent()
    {
        return $this->hasOne(Role::class, 'role_id');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'child_id');
    }

    /** END relationships */


}
