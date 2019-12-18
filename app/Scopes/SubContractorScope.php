<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SubContractorScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where('category_id', 11);
    }

}