<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContactScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->whereNull('role_id')->where('is_employee', 0);
    }

}