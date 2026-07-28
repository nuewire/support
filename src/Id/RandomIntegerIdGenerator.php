<?php

declare(strict_types=1);

namespace Nuewire\Support\Id;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use RuntimeException;

final class RandomIntegerIdGenerator
{
    public function generate(
        Model $model,
        int $min,
        int $max,
        int $attempts = 25,
    ): int {
        if ($min < 1 || $max < $min) {
            throw new InvalidArgumentException('The random integer ID range is invalid.');
        }

        if ($attempts < 1) {
            throw new InvalidArgumentException('The random integer ID attempts must be at least one.');
        }

        $key = $model->getKeyName();

        for ($attempt = 0; $attempt < $attempts; $attempt++) {
            $candidate = random_int($min, $max);

            if (! $model->newQueryWithoutScopes()->where($key, $candidate)->exists()) {
                return $candidate;
            }
        }

        throw new RuntimeException(sprintf(
            'Unable to generate a unique random integer ID for %s after %d attempts.',
            $model::class,
            $attempts,
        ));
    }
}
