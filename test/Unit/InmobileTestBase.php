<?php

namespace Juanparati\Inmobile\Test\Unit;

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
     * Provide Inmobile API client.
     */
    protected function api(): Inmobile
    {
        return $this->app->make(Inmobile::class);
    }
}
