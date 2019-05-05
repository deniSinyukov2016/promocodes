<?php

namespace Itlead\Promocodes\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PromocodeUser extends Pivot
{
	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;
    protected $fillable  = ['user_id', 'promocode_id', 'promocode'];

    /**
     * PromocodeUser constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->table = config('promocodes.relation_table', 'promocode_user');
    }
}
