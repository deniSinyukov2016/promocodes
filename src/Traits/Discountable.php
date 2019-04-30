<?php

namespace Itlead\Promocodes\Traits;

use Itlead\Promocodes\Models\Promocode;
use Itlead\Promocodes\Pivots\PromocodeUser;

trait Discountabble
{
    /**
     * Get the promocodes that are related to user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function promocodes()
    {
        return $this->belongsToMany(Promocode::class, config('promocodes.relation_table'))
        	->using(PromocodeUser::class)
            ->withTimestamps();
    }
}