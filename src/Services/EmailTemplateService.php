<?php

namespace Juanparati\InMobile\Services;

use Juanparati\InMobile\Models\EmailTemplate;
use Juanparati\InMobile\Models\PaginatedResults;

class EmailTemplateService extends InMobileServiceBase
{
    /**
     * Obtain all SMS templates.
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
            $this->api->performRequest('email/templates', 'GET', ['pageLimit' => $limit]),
            EmailTemplate::class
        );
    }

    /**
     * Obtain a specific template.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function find(string $id): ?EmailTemplate
    {
        $model = $this->api->performRequest("email/templates/$id", 'GET');

        return $model ? new EmailTemplate($model) : null;
    }

}
