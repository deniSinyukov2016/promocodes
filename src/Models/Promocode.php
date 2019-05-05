<?php

namespace Itlead\Promocodes\Models;

use Illuminate\Database\Eloquent\Model;
use Itlead\Promocodes\Pivots\PromocodeUser;
use Carbon\Carbon;

class Promocode extends Model
{
    protected $guarded = ['id'];

    protected $dates   = ['expires_at'];

    const FIXED   = 'fixed';
    const PERCENT = 'percent';

  
    /**
     * Promocode constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('promocodes.table', 'promocodes');
    }

	public function users()
    {
        return $this->belongsToMany(config('promocodes.user_model', 'users'))
            ->using(PromocodeUser::class)
            ->withPivot('promocode')
            ->withTimestamps();
    }

    /**
     * Query builder to find promocode using code.
     *
     * @param $query
     * @param $code
     *
     * @return mixed
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Check if code is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at ? Carbon::now()->gte($this->expires_at) : false;
    }
}
