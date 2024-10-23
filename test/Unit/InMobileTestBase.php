<?php

namespace Juanparati\InMobile\Test\Unit;

use Illuminate\Support\Facades\Http;
use Juanparati\InMobile\Facades\InMobileFacade;
use Juanparati\InMobile\InMobile;
use Juanparati\InMobile\Providers\InMobileProvider;
use Orchestra\Testbench\TestCase;

/**
 * Class InMobileTest.
 */
abstract class InMobileTestBase extends TestCase
{
    /**
     * Load service providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [InMobileProvider::class];
    }

    /**
     * Define facades.
     *
     * @return string[]
     */
    protected function getPackageAliases($app): array
    {
        return [
            'InMobile' => InMobileFacade::class,
        ];
    }

    /**
     * Clear fakes before each test.
     *
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        static::clearExistingFakes();
    }

    /**
     * Provide InMobile API client.
     */
    protected function api(): InMobile
    {
        return $this->app->make(InMobile::class);
    }

    /**
     * Clear fakes.
     *
     * @throws \ReflectionException
     */
    protected static function clearExistingFakes(): void
    {
        $realHttp = app(\Illuminate\Http\Client\Factory::class);
        Http::swap($realHttp);
    }

    /**
     * Generated a mocked content.
     */
    protected static function loadMockedResponse(string $response, array $placeholders = []): string
    {
        $mockContent = file_get_contents(__DIR__.'/../MockedResponses/'.$response);

        foreach ($placeholders as $k => $value) {
            $mockContent = str_replace('"'.$k.'"', $value, $mockContent);
        }

        return $mockContent;
    }
}
