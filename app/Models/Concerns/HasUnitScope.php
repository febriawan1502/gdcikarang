<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasUnitScope
{
    protected static function bootHasUnitScope(): void
    {
        static::addGlobalScope('unit', function (Builder $builder) {
            $user = Auth::user();

            if (!$user || !$user->unit_id) {
                return;
            }

            if ($user->unit && $user->unit->is_induk) {
                return;
            }

            $builder->where($builder->getModel()->getTable() . '.unit_id', $user->unit_id);
        });

        static::creating(function ($model) {
            $user = Auth::user();

            if ($user && $user->unit_id && empty($model->unit_id)) {
                $model->unit_id = $user->unit_id;
            }
        });
    }
}
