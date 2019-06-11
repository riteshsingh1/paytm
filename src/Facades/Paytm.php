<?php

namespace imritesh\paytm\Facades;

use Illuminate\Support\Facades\Facade;

class Paytm extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'paytm';
    }
}
