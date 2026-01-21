<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TahunAjaranScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (session()->has('tahun_ajaran_id')) {
            $builder->where($model->getTable() . '.tahun_ajaran_id', session('tahun_ajaran_id'));
        }
    }
}
