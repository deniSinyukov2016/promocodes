<?php
namespace Itlead\Promocodes;

use Itlead\Promocodes\Models\Promocode;
use Itlead\Promocodes\Pivots\PromocodeUser;
use Itlead\Promocodes\Exceptions\InvalidPromocodeException;
use Itlead\Promocodes\Exceptions\AuthorizationException;
use Carbon\Carbon;

class Promocodes
{
	/**
     * Generated codes will be saved here
     * to be validated later.
     *
     * @var array
     */
    private $codes = [];

    /**
     * Separator line
     */
    private $separate = '*';


    private $count_separate = 3;


    /**
     * Promocodes constructor.
     */
    public function __construct()
    {
        $this->codes = Promocode::pluck('code', 'id')->toArray();
    }


    /**
     * Check promocode in database if it is valid.
     *
     * @param string $code
     * @param boolean $single - check with exists code from user
     *
     * @return bool|Promocode
     * @throws InvalidPromocodeException
     */
    public function check($code, $single = false)
    {
        $promocode = Promocode::query()
        	->where('is_supplement', false)
        	->byCode($code)
        	->first();

        if ($supplement = $this->checkSupplement($code)) {
        	$promocode = $supplement[1];
        }

        if ($promocode === null) {
            throw new InvalidPromocodeException;
        }

        if ($single) {
            if ($promocode->users()->exists()) {
               return false; 
            }
        }

        if ($promocode->isExpired() || $promocode->quantity == 0) {
            return false;
        }

        return $promocode;
    }

    /**
     * Check supplement code
     *
     * @param string $code 
     * @return null|string
     */
    public function checkSupplement($code)
    {
    	$delimeter = str_repeat($this->separate, $this->count_separate);
    	$response = [];

	    foreach ($this->getSupplementCodes() as $id => $value) {
    	
	        $match = explode($delimeter, $value);

			if (count($match) < 2) continue;

        	preg_match("~^" . $match[0] . "[a-z]{" . $this->count_separate . "}" . $match[1] . "$~i", $code, $response);

        	if (count($response)) {
				return [$response[0], Promocode::find($id)];        		
        	}
	    }

	    return null;
    }

    /**
     * Get all supplement codes
     * @param int $count - count separate characters
     * @return array All supplement codes
     */
    public function getSupplementCodes()
    {
    	return array_filter($this->codes, function($code) {
    		return preg_match('~.*\\*{'. $this->count_separate .'}.*~i', $code);
    	});
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
	                'promocode_id' => $promocode->id,
                    'promocode'    => $code
	            ]);

	            if ($promocode->quantity > 0) {
        			$promocode->quantity -= 1;
        		}

        		$promocode->save();

	            return [$code, $promocode->refresh()];
	        }
	    } catch (InvalidPromocodeException $exception) {
           	return false;
        }

        return false;
    }

    /**
     * Amount of use of the promo code
     * @param int $id - ID promocode
	 * @return int
     */
    public function countUsed($id)
    {
    	return PromocodeUser::query()
    		->where(config('promocodes.foreign_pivot_key', 'promocode_id'), $id)
    		->count();
    }
}