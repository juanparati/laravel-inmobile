<?php

namespace Juanparati\Inmobile;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Juanparati\Inmobile\Exceptions\InmobileAuthorizationException;
use Juanparati\Inmobile\Exceptions\InmobileRequestException;
use Juanparati\Inmobile\Services\BlacklistService;
use Juanparati\Inmobile\Services\GdprService;
use Juanparati\Inmobile\Services\ListService;
use Juanparati\Inmobile\Services\RecipientService;
use Juanparati\Inmobile\Services\SmsService;
use Juanparati\Inmobile\Services\ToolService;

class Inmobile
{

    /**
     * Default timezone used by inMobile dates.
     */
    const DEFAULT_TIMEZONE = 'UTC';

    /**
     * Default format used by inMobile dates.
     */
    const DEFAULT_DATE_FORMAT = 'Y-m-d\TH:i:s\Z';


    /**
     * Guzzle Http client instance.
     */
    protected Client $client;

    /**
     * API version.
     */
    protected string $version;

    /**
     * Constructor.
     */
    public function __construct(array $config = [])
    {
        $baseUri = $config['base_url'] ?? 'https://api.inmobile.com/';
        $this->version = strtolower($config['version'] ?? 'v4');

        $baseUri = Str::finish($baseUri, '/').Str::finish($this->version, '/');

        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => $config['timeout'] ?? 30,
            'http_errors' => false,
            'auth' => Str::contains($config['api_key'], ':') ? explode(':', $config['api_key']) : ['', $config['api_key']],
        ]);
    }

    /**
     * Perform a request.
     *
     * @throws InmobileAuthorizationException
     * @throws InmobileRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function performRequest(string $endpoint, string $method, array $data = []): ?array
    {
        $response = $this->client->request(
            $method,
            $endpoint,
            in_array($method, ['GET', 'DELETE']) ? ['query' => $data] : ['json' => $data]
        );

        $statusCode = $response->getStatusCode();

        $message = [];

        if (! empty(trim($response->getBody()))) {
            $message = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InmobileRequestException(
                    'Invalid response format: '.$response->getBody(),
                    $response->getStatusCode()
                );
            }
        }

        if ('2' === ((string) $statusCode)[0]) {
            return $message;
        }

        switch ($statusCode) {
            case 500:
            case 400:
                throw new InmobileRequestException($response->getBody(), $statusCode);

            case 401:
                throw new InmobileAuthorizationException($response->getBody(), $statusCode);

            case 404:
                if (! empty($message['errorMessage']) && Str::contains($message['errorMessage'], 'invalid resource', true)) {
                    throw new InmobileRequestException($response->getBody(), $statusCode);
                }
                break;

            default:
                throw new InmobileRequestException('Unknown error: '.$response->getBody(), $statusCode);
        }

        return null;
    }

    /**
     * Provide the lists service.
     */
    public function lists(): ListService
    {
        return new ListService($this);
    }

    /**
     * Provide the recipients service.
     *
     * @return RecipientService
     */
    public function recipients(): RecipientService
    {
        return new RecipientService($this);
    }

    /**
     * Provide SMS service.
     */
    public function sms(): SmsService
    {
        return new SmsService($this);
    }

    /**
     * Provide blacklist service.
     */
    public function blacklist(): BlacklistService
    {
        return new BlacklistService($this);
    }

    /**
     * Provide GDPR service.
     */
    public function gdpr(): GdprService
    {
        return new GdprService($this);
    }

    /**
     * Provide tool service.
     */
    public function tool(): ToolService
    {
        return new ToolService($this);
    }

    /**
     * Retrieve the API version.
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
