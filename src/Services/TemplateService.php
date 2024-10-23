<?php

namespace Juanparati\InMobile\Services;

use Juanparati\InMobile\Models\PaginatedResults;
use Juanparati\InMobile\Models\Template;

class TemplateService extends InMobileServiceBase
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
            $this->api->performRequest('sms/templates', 'GET', ['pageLimit' => $limit]),
            Template::class
        );
    }

    /**
     * Obtain a specific template.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function find(string $id): ?Template
    {
        $model = $this->api->performRequest("sms/templates/$id", 'GET');

        return $model ? new Template($model) : null;
    }

}
