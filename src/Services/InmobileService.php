<?php

namespace Juanparati\Inmobile\Services;

use Juanparati\Inmobile\Inmobile;

interface InmobileService
{
    /**
     * Inmobile api client.
     *
     * @return mixed
     */
    public function __construct(Inmobile $api);
}
