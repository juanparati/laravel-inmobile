<?php

namespace Juanparati\InMobile\Services;

use Juanparati\InMobile\InMobile;

abstract class InMobileServiceBase implements InMobileService
{
    /**
     * Constructor.
     */
    public function __construct(protected InMobile $api) {}
}
