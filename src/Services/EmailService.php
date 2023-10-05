<?php

namespace Juanparati\Inmobile\Services;

use Juanparati\Inmobile\Models\EmailMessage;
use Juanparati\Inmobile\Models\EmailMessageTemplate;
use Juanparati\Inmobile\Models\EmailResponse;

class EmailService extends InmobileServiceBase
{

    /**
     * Send an email.
     *
     * @param EmailMessage $message
     * @return EmailResponse|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function send(EmailMessage $message) : ?EmailResponse {
        $model = $this->api
            ->performRequest('email/outgoing', 'POST', $message->asPostData());

        return $model ? new EmailResponse($model) : null;
    }

    /**
     * Send e-mail template.
     *
     * @param EmailMessageTemplate $message
     * @return EmailResponse|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function sendTemplate(EmailMessageTemplate $message): ?EmailResponse {
        $model = $this->api
            ->performRequest('email/outgoing/sendusingtemplate', 'POST', $message->asPostData());

        return $model ? new EmailResponse($model) : null;
    }




}
