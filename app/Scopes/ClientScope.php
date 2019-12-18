<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ClientScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where('role_id', '=', 8)->where('is_employee', 0);
    }

}