<?php

namespace Juanparati\InMobile\Services;

use Juanparati\InMobile\Models\Message;
use Juanparati\InMobile\Models\MessageTemplate;

class SmsService extends InMobileServiceBase
{
    /**
     * Send SMS messages.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function sendUsingTemplate(string $templateId, array $messages): array
    {
        return $this->api->performRequest('sms/outgoing/sendusingtemplate', 'POST', [
            'templateId' => $templateId,
            'messages'   => array_map(fn (MessageTemplate $r) => $r->asPostData(), $messages),
        ]);
    }

    /**
     * Cancel SMS messages.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function status(int $limit = 250): array
    {
        return $this->api->performRequest('sms/outgoing/reports', 'GET');
    }
}
