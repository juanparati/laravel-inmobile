<?php

namespace Juanparati\Inmobile\Services;

use Juanparati\Inmobile\Inmobile;

abstract class InmobileServiceBase implements InmobileService
{
    /**
     * Constructor.
     */
    public function __construct(protected Inmobile $api)
    {
    }
}
