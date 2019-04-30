<?php
namespace Itlead\Promocodes;

use Itlead\Promocodes\Models\Promocode;
use Itlead\Promocodes\Exceptions\InvalidPromocodeException;
use Itlead\Promocodes\Exceptions\AuthorizationException;
use Carbon\Carbon;

class Promocodes
{
    /**
     * Check promocode in database if it is valid.
     *
     * @param string $code
     *
     * @return bool|Promocode
     * @throws InvalidPromocodeException
     */
    public function check($code)
    {
        $promocode = Promocode::byCode($code)->first();

        if ($promocode === null) {
            throw new InvalidPromocodeException;
        }

        if ($promocode->isExpired() || $promocode->users()->exists() || $promocode->quantity == 0) {
            return false;
        }

        return $promocode;
    }

    /**
     * Apply promocode to user that it's used from now.
     *
     * @param string $code
     *
     * @return bool|Promocode
     * @throws AuthorizationException
     */
    public function apply($code)
    {
        if (!auth()->check()) {
            throw new AuthorizationException;
        }

        try {
	        if ($promocode = $this->check($code)) {

	            $promocode->users()->attach(auth()->id(), [
	                'promocode_id' => $promocode->id
	            ]);

	            return $promocode->load('users');
	        }
	    } catch (InvalidPromocodeException $exception) {
           	return false;
        }

        return false;
    }
}