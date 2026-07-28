<?php

declare(strict_types=1);

namespace Nuewire\Support;

use InvalidArgumentException;

final class NuewirePaths
{
    public function privateDirectory(): string
    {
        return storage_path('app/private/.nuewire');
    }

    public function settingsFile(string $name): string
    {
        return $this->privateDirectory().'/'.$this->segment($name).'.json';
    }

    public function configFile(string $package): string
    {
        return config_path('nuewire/'.$this->segment($package).'.php');
    }

    public function publishedViews(string $package): string
    {
        return resource_path('views/vendor/nuewire/'.$this->segment($package));
    }

    public function publishedTranslations(string $package): string
    {
        return lang_path('vendor/nuewire/'.$this->segment($package));
    }

    private function segment(string $value): string
    {
        $value = trim($value);

        if (preg_match('/^[a-z0-9][a-z0-9_-]*$/', $value) !== 1) {
            throw new InvalidArgumentException('Invalid Nuewire path segment.');
        }

        return $value;
    }
}
