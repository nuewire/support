<?php

declare(strict_types=1);

namespace Nuewire\Support;

use Illuminate\Contracts\Container\Container;

final class LivewireComponentRegistrar
{
    public function __construct(private readonly Container $container)
    {
    }

    /** @param class-string $component */
    public function register(string $name, string $component): bool
    {
        if (! $this->container->bound('livewire')) {
            return false;
        }

        $livewire = $this->container->make('livewire');

        if (method_exists($livewire, 'addComponent')) {
            $livewire->addComponent($name, null, $component);

            return true;
        }

        $facade = 'Livewire\\Livewire';

        if (! class_exists($facade) || ! is_callable([$facade, 'component'])) {
            return false;
        }

        $facade::component($name, $component);

        return true;
    }
}
