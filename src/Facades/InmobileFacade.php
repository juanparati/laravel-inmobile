<?php

namespace Juanparati\Inmobile\Facades;

use Illuminate\Support\Facades\Facade;
use Juanparati\Inmobile\Inmobile;

class InmobileFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Inmobile::class;
    }
}
