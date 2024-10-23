<?php

namespace Juanparati\InMobile\Services;

use Juanparati\InMobile\Models\EmailMessage;
use Juanparati\InMobile\Models\EmailMessageTemplate;
use Juanparati\InMobile\Models\EmailResponse;

class EmailService extends InMobileServiceBase
{
    /**
     * Send an email.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function send(EmailMessage $message): ?EmailResponse
    {
        $model = $this->api
            ->performRequest('email/outgoing', 'POST', $message->asPostData());

        return $model ? new EmailResponse($model) : null;
    }

    /**
     * Send e-mail template.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function sendTemplate(EmailMessageTemplate $message): ?EmailResponse
    {
        $model = $this->api
            ->performRequest('email/outgoing/sendusingtemplate', 'POST', $message->asPostData());

        return $model ? new EmailResponse($model) : null;
    }

}
