<?php

declare(strict_types=1);

namespace Nuewire\Support;

use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

final class LivewireComponentRegistrar
{
    public function __construct(private readonly Container $container)
    {
    }

    /** @param class-string $component */
    public function register(string $name, string $component): bool
    {
        $name = trim($name);

        if ($name === '') {
            throw new InvalidArgumentException('Livewire component name cannot be empty.');
        }

        // Livewire 4 treats `namespace::component` as a namespace lookup before
        // checking explicit class aliases. Flat aliases remain portable across
        // Livewire 3 and 4.
        if (str_contains($name, '::')) {
            throw new InvalidArgumentException(
                "Livewire component alias [{$name}] is not portable. Use a flat alias such as [nuewire-example].",
            );
        }

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
