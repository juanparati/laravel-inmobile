<?php

namespace Juanparati\InMobile\Services;

use Juanparati\InMobile\Exceptions\InMobileRequestException;
use Juanparati\InMobile\Helpers\PhoneCodeHelper;
use Juanparati\InMobile\Models\Recipient;

class RecipientService extends InMobileServiceBase
{
    /**
     * Create recipient.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function deleteById(string $listId, string $id)
    {
        return $this->api->performRequest("lists/$listId/recipients/$id", 'DELETE');
    }

    /**
     * Update recipient by Id.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function updateById(
        string $listId,
        string $id,
        Recipient $recipient,
        bool $overwriteAll = false
    ): Recipient {
        $payload = $recipient->asPostData();

        return new Recipient(
            $this->api->performRequest(
                "lists/$listId/recipients/$id",
                'PUT',
                $overwriteAll ? $payload : $recipient::cleanNullableData($payload)
            )
        );
    }

    /**
     * Update recipient by number.
     *
     * @throws InMobileRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     */
    public function updateByNumber(
        string $listId,
        string|int $code,
        string|int $phone,
        Recipient $recipient,
        bool $overwriteAll = false
    ): Recipient {
        $recipientId = $this->findByNumber($listId, $code, $phone)?->getId();

        if (! $recipientId) {
            throw new InMobileRequestException(
                "Could not find recipient in list: $listId",
                404
            );
        }

        return $this->updateById($listId, $recipientId, $recipient, $overwriteAll);
    }

    /**
     * Delete recipient by number.
     *
     * @return array|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
