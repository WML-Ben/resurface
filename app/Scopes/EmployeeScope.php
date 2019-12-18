<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EmployeeScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->whereNotNull('role_id')->where('role_id', '>', 2)->where('is_employee', 1);
    }

}