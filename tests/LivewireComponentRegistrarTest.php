<?php

declare(strict_types=1);

namespace Nuewire\Support\Tests;

use InvalidArgumentException;
use Nuewire\Support\LivewireComponentRegistrar;

final class LivewireComponentRegistrarTest extends TestCase
{
    public function test_it_registers_a_livewire_four_component_when_the_registry_is_available(): void
    {
        $registry = new FakeLivewireRegistry();
        $this->app->instance('livewire', $registry);

        $registered = app(LivewireComponentRegistrar::class)->register('nuewire-demo', DemoComponent::class);

        $this->assertTrue($registered);
        $this->assertSame(DemoComponent::class, $registry->components['nuewire-demo']);
    }

    public function test_it_rejects_double_colon_aliases_that_livewire_four_interprets_as_namespaces(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is not portable');

        app(LivewireComponentRegistrar::class)->register('nuewire::demo', DemoComponent::class);
    }
}

final class FakeLivewireRegistry
{
    /** @var array<string, class-string> */
    public array $components = [];

    /** @param class-string $component */
    public function addComponent(string $name, mixed $path, string $component): void
    {
        $this->components[$name] = $component;
    }
}

final class DemoComponent
{
}
