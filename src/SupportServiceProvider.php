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

        $this->registerPlatformDashboard();
    }


    private function registerPlatformDashboard(): void
    {
        $registryClass = 'Nuewire\\Platform\\Dashboard\\DashboardRegistry';

        $this->app->afterResolving($registryClass, static function (object $registry): void {
            if (! method_exists($registry, 'register')) {
                return;
            }

            if (method_exists($registry, 'registerGroup')) {
                $registry->registerGroup('runtime', [
                    'label' => ['id' => 'Runtime', 'en' => 'Runtime'],
                    'order' => 85,
                ]);
            }

            $registry->register('support.runtime', [
                'group' => 'runtime',
                'label' => ['id' => 'Runtime Nuewire', 'en' => 'Nuewire Runtime'],
                'description' => ['id' => 'Versi runtime inti dan konfigurasi ID acak.', 'en' => 'Core runtime versions and random ID configuration.'],
                'type' => 'status',
                'permission' => 'platform.manage',
                'width' => 4,
                'default' => false,
                'cache_ttl' => 300,
                'cache_scope' => 'global',
                'resolver' => static function (object $context): array {
                    $min = (int) config('nuewire.support.random_integer_id.min', 100000000);
                    $max = (int) config('nuewire.support.random_integer_id.max', 999999999);

                    return [
                        'status' => 'healthy',
                        'headline' => $context->locale === 'en' ? 'Support services ready' : 'Layanan support siap',
                        'message' => $context->locale === 'en' ? 'Shared Nuewire infrastructure is registered.' : 'Infrastruktur bersama Nuewire telah terdaftar.',
                        'items' => [
                            ['label' => 'Laravel', 'value' => app()->version()],
                            ['label' => 'PHP', 'value' => PHP_VERSION],
                            ['label' => 'Random ID', 'value' => number_format($min).'–'.number_format($max)],
                        ],
                    ];
                },
                'order' => 10,
            ]);
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/nuewire/support.php' => config_path('nuewire/support.php'),
        ], 'nuewire-support-config');
    }
}
