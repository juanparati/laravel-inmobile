<?php

namespace Juanparati\Inmobile\Services;

use Juanparati\Inmobile\Models\EmailTemplate;
use Juanparati\Inmobile\Models\PaginatedResults;

class EmailTemplateService extends InmobileServiceBase
{
    /**
     * Obtain all SMS templates.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
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
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function find(string $id): ?EmailTemplate
    {
        $model = $this->api->performRequest("email/templates/$id", 'GET');

        return $model ? new EmailTemplate($model) : null;
    }

}
