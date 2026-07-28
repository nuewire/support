<?php

declare(strict_types=1);

namespace Nuewire\Support;

use Illuminate\Support\ServiceProvider;
use Nuewire\Support\Id\RandomIntegerIdGenerator;
use Nuewire\Support\LivewireComponentRegistrar;
use Nuewire\Support\NuewirePaths;

final class SupportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->replaceConfigRecursivelyFrom(
            __DIR__.'/../config/nuewire/support.php',
            'nuewire.support',
        );

        $this->app->singleton(RandomIntegerIdGenerator::class);
        $this->app->singleton(NuewirePaths::class);
        $this->app->singleton(LivewireComponentRegistrar::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/nuewire/support.php' => config_path('nuewire/support.php'),
        ], 'nuewire-support-config');
    }
}
