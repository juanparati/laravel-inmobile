<?php

namespace Juanparati\InMobile\Services;

use GuzzleHttp\Exception\GuzzleException;
use Juanparati\InMobile\Exceptions\InMobileAuthorizationException;
use Juanparati\InMobile\Exceptions\InMobileRequestException;
use Juanparati\InMobile\Helpers\PhoneCodeHelper;

class GdprService extends InMobileServiceBase
{
    /**
     * Create deletion requests.
     *
     *
     * @throws GuzzleException
     * @throws InMobileAuthorizationException
     * @throws InMobileRequestException
     */
    public function create(string|int $code, string|int $phone): ?array
    {
        return $this->api->performRequest('sms/gdpr/deletionrequests', 'POST', [
            'numberInfo' => [
                'countryCode' => PhoneCodeHelper::sanitize($code),
                'phoneNumber' => $phone,
            ],
        ]);
    }
}
