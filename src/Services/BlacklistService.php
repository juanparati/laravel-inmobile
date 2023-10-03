<?php

namespace Juanparati\Inmobile\Services;

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
    public function createEntry(Blacklist $blacklist): ?Blacklist
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
    public function getEntryById(string $id): ?Blacklist
    {
        $model = $this->api->performRequest("blacklist/$id", 'GET');

        return $model ? new Blacklist($model) : null;
    }

    /**
     * Delete entry by id.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function deleteEntryById(string $id): array
    {
        return $this->api->performRequest("blacklist/$id", 'DELETE');
    }

    /**
     * Get entry by number.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function getEntryByNumber(string|int $code, string|int $phone): ?Blacklist
    {
        $model = $this->api->performRequest('blacklist/ByNumber', 'GET', [
            'countryCode' => $code,
            'phoneNumber' => $phone,
        ]);

        return $model ? new Blacklist($model) : null;
    }

    /**
     * Delete entry by number.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function deleteEntryByNumber(string|int $code, string|int $phone): array
    {
        return $this->api->performRequest('blacklist/ByNumber', 'DELETE', [
            'countryCode' => $code,
            'phoneNumber' => $phone,
        ]);
    }
}
