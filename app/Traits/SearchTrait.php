<?php

trait SearchTrait
{
    public abstract function searchableColumns();

    public function scopeSearch($query, $needle)
    {
        if ( ! empty($needle)) {
            $query->where(function($q) use ($needle) {
                foreach ($this->searchableColumns() as $field => $operator) {
                    if ($field == 'childModels') {
                        foreach ($operator as $modelName => $content) {
                            $fields = $content['fields'];
                            $q->orWhereHas($modelName, function ($z) use ($needle, $modelName, $fields) {
                                $z->Where(function($w) use ($needle, $fields) {
                                    foreach ($fields as $f => $op) {
                                        if ($op == 'LIKE') {
                                            $w->orWhere($f, $op, '%' . $needle . '%');
                                        } else {
                                            $w->orWhere($f, $op, $needle);
                                        }
                                    }
                                });
                            });
                        }
                    } else if ($field == 'childModel') {
                        $modelName = $operator['modelName'];
                        $fields = $operator['fields'];
                        $q->orWhereHas($modelName, function ($z) use ($needle, $modelName, $fields) {
                            $z->Where(function($w) use ($needle, $fields) {
                                foreach ($fields as $f => $op) {
                                    if ($op == 'LIKE') {
                                        $w->orWhere($f, $op, '%' . $needle . '%');
                                    } else {
                                        $w->orWhere($f, $op, $needle);
                                    }
                                }
                            });
                        });
                    } else if ($field == 'childModelsThrough') {
                        foreach ($operator as $modelName => $content) {
                            $through = $content['through'];
                            $fields = $content['fields'];
                            $q->orWhereHas($through, function($r) use ($needle, $modelName, $fields){
                                $r->orWhereHas($modelName, function ($z) use ($needle, $modelName, $fields) {
                                    $z->Where(function($w) use ($needle, $fields) {
                                        foreach ($fields as $f => $op) {
                                            if ($op == 'LIKE') {
                                                $w->orWhere($f, $op, '%' . $needle . '%');
                                            } else {
                                                $w->orWhere($f, $op, $needle);
                                            }
                                        }
                                    });
                                });
                            });
                        }
                    } else {
                        if ($operator == 'LIKE') {
                            $q->orWhere($field, $operator, '%' . $needle . '%');
                        } else {
                            $q->orWhere($field, $operator, $needle);
                        }
                    }
                }
            });
        }

        return $query;
    }

}