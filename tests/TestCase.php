<?php

namespace WdevRs\LaravelDatagrid\Tests;

use WdevRs\LaravelDatagrid\LaravelDatagridServiceProvider;

/**
 * Class TestCase base Class for test cases
 */
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDatagridServiceProvider::class
        ];
    }
}
