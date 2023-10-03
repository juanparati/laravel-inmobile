<?php

namespace Juanparati\Inmobile\Services;

use Juanparati\Inmobile\Models\Message;
use Juanparati\Inmobile\Models\MessageTemplate;

class SmsService extends InmobileServiceBase
{
    /**
     * Send SMS messages.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function send(array $messages): array
    {
        return $this->api->performRequest('sms/outgoing', 'POST', [
            'messages' => array_map(fn (Message $r) => $r->asPostData(), $messages),
        ]);
    }

    /**
     * Send an SMS using a template.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function sendUsingTemplate(string $templateId, array $messages): array
    {
        return $this->api->performRequest('sms/outgoing/sendusingtemplate', 'POST', [
            'templateId' => $templateId,
            'messages' => array_map(fn (MessageTemplate $r) => $r->asPostData(), $messages),
        ]);
    }

    /**
     * Cancel SMS messages.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function cancel(array $messageIds): array
    {
        return $this->api->performRequest('sms/outgoing/cancel', 'POST', [
            'messageIds' => $messageIds,
        ]);
    }

    /**
     * Obtain SMS reports.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function status(int $limit = 250): array
    {
        return $this->api->performRequest('sms/outgoing/reports', 'GET');
    }
}
