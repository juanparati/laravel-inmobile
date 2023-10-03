<?php

namespace Juanparati\Inmobile\Services;

use GuzzleHttp\Exception\GuzzleException;
use Juanparati\Inmobile\Exceptions\InmobileAuthorizationException;
use Juanparati\Inmobile\Exceptions\InmobileRequestException;
use Juanparati\Inmobile\Helpers\PhoneCodeHelper;

class GdprService extends InmobileServiceBase
{
    /**
     * Create deletion requests.
     *
     * @param string|int $code
     * @param string|int $phone
     * @return array|null
     *
     * @throws GuzzleException
     * @throws InmobileAuthorizationException
     * @throws InmobileRequestException
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
