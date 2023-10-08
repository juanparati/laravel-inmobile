<?php

namespace Juanparati\Inmobile\Services;

use Juanparati\Inmobile\Models\PaginatedResults;
use Juanparati\Inmobile\Models\Template;

class TemplateService extends InmobileServiceBase
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
            $this->api->performRequest('sms/templates', 'GET', ['pageLimit' => $limit]),
            Template::class
        );
    }

    /**
     * Obtain a specific template.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function find(string $id): ?Template
    {
        $model = $this->api->performRequest("sms/templates/$id", 'GET');

        return $model ? new Template($model) : null;
    }

}
