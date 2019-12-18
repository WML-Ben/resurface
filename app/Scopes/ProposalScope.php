<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProposalScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->whereIn('orders.status_id', [1, 2, 3, 4, 10]);
    }

}