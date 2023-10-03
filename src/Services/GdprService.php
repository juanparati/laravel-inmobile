<?php

namespace Juanparati\Inmobile\Services;

class GdprService extends InmobileServiceBase
{
    /**
     * Create deletion requests.
     *
     * @return array|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function create(string|int $code, string|int $phone)
    {
        return $this->api->performRequest('sms/gdpr/deletionrequests', 'POST', [
            'numberInfo' => [
                'countryCode' => $code,
                'phoneNumber' => $phone,
            ],
        ]);
    }
}
