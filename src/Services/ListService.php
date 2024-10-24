<?php

namespace Juanparati\InMobile\Services;

use GuzzleHttp\Exception\GuzzleException;
use Juanparati\InMobile\Exceptions\InMobileAuthorizationException;
use Juanparati\InMobile\Exceptions\InMobileRequestException;
use Juanparati\InMobile\Models\PaginatedResults;
use Juanparati\InMobile\Models\Recipient;
use Juanparati\InMobile\Models\RecipientList;

class ListService extends InMobileServiceBase
{
    /**
     * Get all the lists paginated.
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
            $this->api->performRequest('lists', 'GET', ['pageLimit' => $limit]),
            RecipientList::class
        );
    }

    /**
     * Get a specific list.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function find(string $listId): ?RecipientList
    {
        $model = $this->api->performRequest('lists/'.$listId, 'GET');

        return $model ? new RecipientList($model) : null;
    }

    /**
     * Create a new list.
     *
     * @return RecipientList
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function create(string $name)
    {
        return new RecipientList(
            $this->api->performRequest('lists', 'POST', ['name' => $name])
        );
    }

    /**
     * Delete lists.
     *
     *
     * @throws GuzzleException
     * @throws InMobileAuthorizationException
     * @throws InMobileRequestException
     */
    public function delete(string $listId): ?array
    {
        return $this->api->performRequest('lists/'.$listId, 'DELETE');
    }

    /**
     * Update list name.
     *
     * @return RecipientList
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function update(string $listId, string $name)
    {
        return new RecipientList(
            $this->api->performRequest("lists/$listId", 'PUT', RecipientList::make($name)->asPostData())
        );
    }

    /**
     * Get all list of recipients from a specific list.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function getRecipients(string $listId, int $limit = 250): ?PaginatedResults
    {
        $limit = $limit ?: 1;
        $limit = min($limit, 250);

        return new PaginatedResults(
            $this->api,
            $this->api->performRequest("lists/$listId/recipients", 'GET', ['pageLimit' => $limit]),
            Recipient::class
        );
    }

    /**
     * Delete all recipients from a list.
     *
     * @return array|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function truncate(string $listId)
    {
        return $this->api->performRequest("lists/$listId/recipients/all", 'DELETE');
    }
}
