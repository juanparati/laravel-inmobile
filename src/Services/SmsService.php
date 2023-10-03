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
    public function sendSMSMessages(array $messages): array
    {
        return $this->api->performRequest('sms/outgoing', 'POST', [
            'messages' => array_map(fn (Message $r) => $r->asPostData(), $messages),
        ]);
    }

    /**
     * Send a SMS using a template.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function sendSMSMessagesUsingTemplate(string $templateId, array $messages): array
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
    public function cancelSMSMessages(array $messageIds): array
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
    public function statusReports(int $limit = 250): array
    {
        return $this->api->performRequest('sms/outgoing/reports', 'GET');
    }
}
