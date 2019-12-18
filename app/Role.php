<?php namespace App;

class Role extends BaseModel
{
    
    public $timestamps = false;

    protected $fillable = [
        'name',
        'disabled',
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

    public function children()
    {
        return $this->hasMany(RoleChildren::class);
    }

    /** END relationships */

    /** Methods, Accessor(get) and Mutators(set) */

    public static function getRoleIdByName($roleName)
    {
        return self::where('name', $roleName)->first();
    }

}
