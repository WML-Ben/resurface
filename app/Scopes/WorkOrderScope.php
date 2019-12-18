<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WorkOrderScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->whereIn('orders.status_id', [5, 6, 7, 8, 9]);
    }

}