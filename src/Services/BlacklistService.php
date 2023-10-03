<?php

namespace Juanparati\Inmobile\Services;

use Juanparati\Inmobile\Helpers\PhoneCodeHelper;
use Juanparati\Inmobile\Models\Blacklist;
use Juanparati\Inmobile\Models\PaginatedResults;

class BlacklistService extends InmobileServiceBase
{
    /**
     * Get list of blacklisted.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
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
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
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
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
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
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
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
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function deleteById(string $id): array
    {
        return $this->api->performRequest("blacklist/$id", 'DELETE');
    }

    /**
     * Delete entry by number.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function deleteByNumber(string|int $code, string|int $phone): array
    {
        return $this->api->performRequest('blacklist/ByNumber', 'DELETE', [
            'countryCode' => PhoneCodeHelper::sanitize($code),
            'phoneNumber' => $phone,
        ]);
    }
}
