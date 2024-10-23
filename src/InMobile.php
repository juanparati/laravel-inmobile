<?php

namespace Juanparati\InMobile;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Juanparati\InMobile\Exceptions\InMobileAuthorizationException;
use Juanparati\InMobile\Exceptions\InMobileRequestException;
use Juanparati\InMobile\Services\BlacklistService;
use Juanparati\InMobile\Services\EmailService;
use Juanparati\InMobile\Services\EmailTemplateService;
use Juanparati\InMobile\Services\GdprService;
use Juanparati\InMobile\Services\ListService;
use Juanparati\InMobile\Services\RecipientService;
use Juanparati\InMobile\Services\SmsService;
use Juanparati\InMobile\Services\TemplateService;
use Juanparati\InMobile\Services\ToolService;

class InMobile
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
     * @throws InMobileAuthorizationException
     * @throws InMobileRequestException
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

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InMobileRequestException(
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
                throw new InMobileRequestException($response->getBody(), $statusCode);
            case 401:
                throw new InMobileAuthorizationException($response->getBody(), $statusCode);
            case 404:
                if ($method === 'PUT' || (
                    ! empty($message['errorMessage']) &&
                    Str::contains($message['errorMessage'], 'invalid resource', true)
                )) {
                    throw new InMobileRequestException($response->getBody(), $statusCode);
                }
                break;

            default:
                throw new InMobileRequestException('Unknown error: '.$response->getBody(), $statusCode);
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
