<?php

namespace Juanparati\Inmobile\Test\Unit;

use Illuminate\Support\Facades\Http;
use Juanparati\Inmobile\Facades\InmobileFacade;
use Juanparati\Inmobile\Inmobile;
use Juanparati\Inmobile\Providers\InmobileProvider;
use Orchestra\Testbench\TestCase;

/**
 * Class InmobileTest.
 */
abstract class InmobileTestBase extends TestCase
{
    /**
     * Load service providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [InmobileProvider::class];
    }

    /**
     * Define facades.
     *
     * @return string[]
     */
    protected function getPackageAliases($app)
    {
        return [
            'Inmobile' => InmobileFacade::class,
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
     * Provide Inmobile API client.
     */
    protected function api(): Inmobile
    {
        return $this->app->make(Inmobile::class);
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
