<?php

namespace Juanparati\Inmobile\Services;

use Juanparati\Inmobile\Helpers\PhoneCodeHelper;
use Juanparati\Inmobile\Models\Recipient;

class RecipientService extends InmobileServiceBase
{
    /**
     * Create recipient.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function create(string $listId, Recipient $recipient): ?Recipient
    {
        $model = $this->api->performRequest(
            "lists/$listId/recipients",
            'POST', $recipient->asPostData()
        );

        return $model ? new Recipient($model) : null;
    }

    /**
     * Get recipient by Id.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function findById(string $listId, string $id): ?Recipient
    {
        $model = $this->api->performRequest("lists/$listId/recipients/$id", 'GET');

        return $model ? new Recipient($model) : null;
    }

    /**
     * Get recipient by number.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function findByNumber(
        string $listId,
        string|int $code,
        string|int $phone
    ): ?Recipient {
        $model = $this->api->performRequest("lists/$listId/recipients/ByNumber", 'GET', [
            'countryCode' => PhoneCodeHelper::sanitize($code),
            'phoneNumber' => $phone,
        ]);

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
    public function deleteById(string $listId, string $id)
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
    public function updateById(
        string $listId,
        string $id,
        Recipient $recipient
    ) {
        return new Recipient(
            $this->api->performRequest("lists/$listId/recipients/$id", 'PUT', $recipient->asPostData())
        );
    }

    /**
     * Delete recipient by number.
     *
     * @return array|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function deleteByNumber(
        string $listId,
        string|int $code,
        string|int $phone
    ) {
        return $this->api->performRequest("lists/$listId/recipients/ByNumber", 'DELETE', [
            'countryCode' => PhoneCodeHelper::sanitize($code),
            'phoneNumber' => $phone,
        ]);
    }

    /**
     * Update or create recipient by number.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function updateOrCreateByNumber(
        string $listId,
        string|int $code,
        string|int $phone,
        Recipient $recipient
    ): Recipient {
        return new Recipient(
            $this->api->performRequest(
                sprintf(
                    'lists/%s/recipients/ByNumber?countryCode=%s&phoneNumber=%s',
                    $listId,
                    PhoneCodeHelper::sanitize($code),
                    $phone
                ),
                'POST',
                $recipient->asPostData()
            )
        );
    }

    /**
     * Move recipient from list to another.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function moveToList(string $srcListId, string $dstListId, string $id): ?Recipient
    {
        $rcpSrc = $this->findById($srcListId, $id);

        if (! $rcpSrc) {
            return null;
        }

        $rpcDst = $this->updateOrCreateByNumber(
            $dstListId,
            $rcpSrc->getCode(),
            $rcpSrc->getPhone(),
            $rcpSrc
        );

        $this->deleteById($srcListId, $rcpSrc->getId());

        return $rpcDst;
    }

}
