<?php

namespace Juanparati\InMobile\Services;

use Juanparati\InMobile\InMobile;

interface InMobileService
{
    /**
     * InMobile api client.
     *
     * @return mixed
     */
    public function __construct(InMobile $api);
}
