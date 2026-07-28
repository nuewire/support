<?php

declare(strict_types=1);

namespace Nuewire\Support\Concerns;

use Illuminate\Database\Eloquent\Model;
use Nuewire\Support\Id\RandomIntegerIdGenerator;

trait HasRandomIntegerId
{
    public static function bootHasRandomIntegerId(): void
    {
        static::creating(static function (Model $model): void {
            $key = $model->getKeyName();
            $current = $model->getAttribute($key);

            if ($current !== null && $current !== '') {
                return;
            }

            $generator = app(RandomIntegerIdGenerator::class);

            $model->setAttribute($key, $generator->generate(
                $model,
                $model->nuewireRandomIntegerIdMinimum(),
                $model->nuewireRandomIntegerIdMaximum(),
                $model->nuewireRandomIntegerIdAttempts(),
            ));
        });
    }

    public function initializeHasRandomIntegerId(): void
    {
        $this->setIncrementing(false);
        $this->setKeyType('int');
    }

    protected function nuewireRandomIntegerIdMinimum(): int
    {
        return (int) config('nuewire.support.random_integer_id.min', 100_000_000);
    }

    protected function nuewireRandomIntegerIdMaximum(): int
    {
        return (int) config('nuewire.support.random_integer_id.max', 999_999_999);
    }

    protected function nuewireRandomIntegerIdAttempts(): int
    {
        return (int) config('nuewire.support.random_integer_id.attempts', 25);
    }
}
