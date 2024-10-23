<?php

namespace Juanparati\InMobile\Services;

use Juanparati\InMobile\Helpers\PhoneCodeHelper;
use Juanparati\InMobile\Models\Blacklist;
use Juanparati\InMobile\Models\PaginatedResults;

class BlacklistService extends InMobileServiceBase
{
    /**
     * Get list of blacklisted.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function all(int $limit = 250): ?PaginatedResults
    {
        $limit = $limit ?: 1;
        $limit = min($limit, 250);

        return new PaginatedResults(
            $this->api,
            $this->api->performRequest('blacklist', 'GET', [
                'pageLimit' => $limit,
            ]),
            Blacklist::class
        );
    }

    /**
     * Add new blacklist entry.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function create(Blacklist $blacklist): ?Blacklist
    {
        $model = $this->api->performRequest('blacklist', 'POST', $blacklist->asPostData());

        return $model ? new Blacklist($model) : null;
    }

    /**
     * Get entry by id.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function findById(string $id): ?Blacklist
    {
        $model = $this->api->performRequest("blacklist/$id", 'GET');

        return $model ? new Blacklist($model) : null;
    }

    /**
     * Get entry by number.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function findByNumber(string|int $code, string|int $phone): ?Blacklist
    {
        $model = $this->api->performRequest('blacklist/ByNumber', 'GET', [
            'countryCode' => PhoneCodeHelper::sanitize($code),
            'phoneNumber' => $phone,
        ]);

        return $model ? new Blacklist($model) : null;
    }

    /**
     * Delete entry by id.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function deleteById(string $id): array
    {
        return $this->api->performRequest("blacklist/$id", 'DELETE');
    }

    /**
     * Delete entry by number.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function deleteByNumber(string|int $code, string|int $phone): array
    {
        return $this->api->performRequest('blacklist/ByNumber', 'DELETE', [
            'countryCode' => PhoneCodeHelper::sanitize($code),
            'phoneNumber' => $phone,
        ]);
    }
}
