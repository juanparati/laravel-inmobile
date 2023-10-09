<?php

namespace Juanparati\Inmobile;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Juanparati\Inmobile\Exceptions\InmobileAuthorizationException;
use Juanparati\Inmobile\Exceptions\InmobileRequestException;
use Juanparati\Inmobile\Services\BlacklistService;
use Juanparati\Inmobile\Services\EmailService;
use Juanparati\Inmobile\Services\EmailTemplateService;
use Juanparati\Inmobile\Services\GdprService;
use Juanparati\Inmobile\Services\ListService;
use Juanparati\Inmobile\Services\RecipientService;
use Juanparati\Inmobile\Services\SmsService;
use Juanparati\Inmobile\Services\TemplateService;
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
    //protected Client $client;
    protected PendingRequest $client;

    /**
     * API version.
     */
    protected readonly string $version;

    /**
     * Base URI.
     */
    protected readonly string $baseUri;

    /**
     * Constructor.
     */
    public function __construct(array $config = [])
    {
        $baseUri       = $config['base_url'] ?? 'https://api.inmobile.com/';
        $this->version = strtolower($config['version'] ?? 'v4');
        $this->baseUri = Str::finish($baseUri, '/').Str::finish($this->version, '/');

        [$username, $key] = Str::contains($config['api_key'], ':') ? explode(':', $config['api_key']) : ['', $config['api_key']];

        $this->client = Http::baseUrl($this->baseUri)
            ->timeout($config['timeout'] ?? 30)
            ->withBasicAuth($username, $key);
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
        $response = $this->getHttpClient()
            ->send(
                $method,
                $endpoint,
                in_array($method, ['GET', 'DELETE']) ? ['query' => $data] : ['json' => $data]
            );

        $statusCode = $response->status();

        $message = [];

        if (! empty(trim($response->body()))) {
            $message = $response->json();

            if (JSON_ERROR_NONE !== json_last_error()) {
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
     * Provides e-mail service.
     */
    public function email(): EmailService
    {
        return new EmailService($this);
    }

    /**
     * Provides SMS template service.
     */
    public function templates(): TemplateService
    {
        return new TemplateService($this);
    }

    /**
     * Provides Email template service.
     */
    public function emailTemplates(): EmailTemplateService
    {
        return new EmailTemplateService($this);
    }

    /**
     * Provides the http client instance.
     */
    public function getHttpClient(): PendingRequest
    {
        return $this->client;
    }

    /**
     * Retrieve the API version.
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Retrieve base URI.
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }
}
