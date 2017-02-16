<?php

use thePLAN\DirectusLaravel\DirectusLaravel;
use thePLAN\DirectusLaravel\DirectusLaravelServiceProvider;
use Orchestra\Testbench\TestCase;

class SetupTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            DirectusLaravelServiceProvider::class
        ];
    }

    public function testAPISetup()
    {
        $provider = new DirectusLaravelServiceProvider($this->app);
        $this->assertContains('directuslaravel', $provider->provides());
    }
}