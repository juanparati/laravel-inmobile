<?php

namespace Juanparati\InMobile\Facades;

use Illuminate\Support\Facades\Facade;

class InMobileFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'inmobile';
    }
}
