<?php

namespace WdevRs\LaravelDatagrid\Tests;

use Orchestra\Testbench\TestCase;
use WdevRs\LaravelDatagrid\LaravelDatagridServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelDatagridServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
