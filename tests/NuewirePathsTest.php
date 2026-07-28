<?php

declare(strict_types=1);

namespace Nuewire\Support\Tests;

use Nuewire\Support\NuewirePaths;

final class NuewirePathsTest extends TestCase
{
    public function test_shared_paths_follow_the_nuewire_directory_convention(): void
    {
        $paths = app(NuewirePaths::class);

        $this->assertStringEndsWith('/storage/app/private/.nuewire/users.json', $paths->settingsFile('users'));
        $this->assertStringEndsWith('/config/nuewire/users.php', $paths->configFile('users'));
        $this->assertStringEndsWith('/resources/views/vendor/nuewire/users', $paths->publishedViews('users'));
        $this->assertStringEndsWith('/lang/vendor/nuewire/users', $paths->publishedTranslations('users'));
    }
}
