<?php

namespace SevenSparky\LaravelMobileAuth\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class LaravelMobileAuthFacade
 *
 * @method static string sayHi
 *
 * @see \SevenSparky\LaravelMobileAuth\
 * @package SevenSparky\LaravelMobileAuth\Facade
 */
class LaravelMobileAuthFacade extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'LaravelMobileAuth';
    }


}
