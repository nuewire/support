<?php

declare(strict_types=1);

namespace Nuewire\Support\Tests;

use Nuewire\Support\SupportServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [SupportServiceProvider::class];
    }
}
