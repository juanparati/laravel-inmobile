<?php

namespace Juanparati\Inmobile\Services;

use GuzzleHttp\Exception\GuzzleException;
use Juanparati\Inmobile\Exceptions\InmobileAuthorizationException;
use Juanparati\Inmobile\Exceptions\InmobileRequestException;
use Juanparati\Inmobile\Models\PaginatedResults;
use Juanparati\Inmobile\Models\Recipient;
use Juanparati\Inmobile\Models\RecipientList;

class ListService extends InmobileServiceBase
{
    /**
     * Get all the lists paginated.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function getAll(int $limit = 250): ?PaginatedResults
    {
        $limit = $limit ?: 1;
        $limit = $limit > 250 ? 250 : $limit;

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
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function getList(string $listId): ?RecipientList
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
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function createList(string $name)
    {
        return new RecipientList(
            $this->api->performRequest('lists', 'POST', ['name' => $name])
        );
    }

    /**
     * Delete lists.
     *
     * @return array|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function deleteList(string $listId)
    {
        return $this->api->performRequest('lists/'.$listId, 'DELETE');
    }

    /**
     * Update list name.
     *
     * @return RecipientList
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function updateList(string $listId, string $name)
    {
        return new RecipientList(
            $this->api->performRequest('lists', 'PUT', RecipientList::make($name)->asPostData())
        );
    }

    /**
     * Get all list of recipients.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function getRecipientsInList(string $listId, int $limit = 250): ?PaginatedResults
    {
        $limit = $limit ?: 1;
        $limit = $limit > 250 ? 250 : $limit;

        return new PaginatedResults(
            $this->api,
            $this->api->performRequest("lists/$listId/recipients", 'GET', ['pageLimit' => $limit]),
            Recipient::class
        );
    }

    /**
     * Create recipient.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function createRecipient(string $listId, Recipient $recipient): ?Recipient
    {
        $model = $this->api->performRequest(
            "lists/$listId/recipients",
            'POST', $recipient->asPostData()
        );

        return $model ? new Recipient($model) : null;
    }

    /**
     * Delete all recipients from a list.
     *
     * @return array|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function deleteAllRecipientsFromList(string $listId)
    {
        return $this->api->performRequest("lists/$listId/recipients/all", 'DELETE');
    }

    /**
     * Get recipient by Id.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function getRecipientById(string $listId, string $id): ?Recipient
    {
        $model = $this->api->performRequest("lists/$listId/recipients/$id", 'GET');

        return $model ? new Recipient($model) : null;
    }

    /**
     * Delete recipient by Id.
     *
     * @return array|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function deleteRecipientById(string $listId, string $id)
    {
        return $this->api->performRequest("lists/$listId/recipients/$id", 'DELETE');
    }

    /**
     * Update recipient by Id.
     *
     * @return Recipient
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function updateRecipientById(
        string $listId,
        string $id,
        Recipient $recipient
    ) {
        return new Recipient(
            $this->api->performRequest("lists/$listId/recipients/$id", 'PUT', $recipient->asPostData())
        );
    }

    /**
     * Get recipient by number.
     *
     * @throws GuzzleException
     * @throws InmobileAuthorizationException
     * @throws InmobileRequestException
     */
    public function getRecipientByNumber(
        string $listId,
        string|int $code,
        string|int $phone
    ): ?Recipient {
        $model = $this->api->performRequest("lists/$listId/recipients/ByNumber", 'GET', [
            'countryCode' => $code,
            'phoneNumber' => $phone,
        ]);

        return $model ? new Recipient($model) : null;
    }

    /**
     * Delete recipient by number.
     *
     * @return array|null
     *
     * @throws GuzzleException
     * @throws InmobileAuthorizationException
     * @throws InmobileRequestException
     */
    public function deleteRecipientByNumber(
        string $listId,
        string|int $code,
        string|int $phone
    ) {
        return $this->api->performRequest("lists/$listId/recipients/ByNumber", 'DELETE', [
            'countryCode' => $code,
            'phoneNumber' => $phone,
        ]);
    }

    /**
     * Update or create recipient by number.
     *
     * @throws GuzzleException
     * @throws InmobileAuthorizationException
     * @throws InmobileRequestException
     */
    public function updateOrCreateRecipientByNumber(
        string $listId,
        string|int $code,
        string|int $phone,
        Recipient $recipient
    ): Recipient {
        return new Recipient(
            $this->api->performRequest(
                "lists/$listId/recipients/ByNumber?countryCode=$code&phoneNumber=$phone",
                'POST',
                $recipient->asPostData()
            )
        );
    }

    /**
     * Move recipient from list to another.
     *
     * @throws GuzzleException
     * @throws InmobileAuthorizationException
     * @throws InmobileRequestException
     */
    public function moveRecipientToList(string $srcListId, string $dstListId, string $id): ?Recipient
    {
        $rcpSrc = $this->getRecipientById($srcListId, $id);

        if (! $rcpSrc) {
            return null;
        }

        $rpcDst = $this->createRecipient($dstListId, $rcpSrc);

        if ($rpcDst) {
            $this->deleteRecipientById($srcListId, $rcpSrc->getId());
        }

        return $rpcDst;
    }
}
