<?php

declare(strict_types=1);

namespace Nuewire\Support\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nuewire\Support\Concerns\HasRandomIntegerId;

final class RandomIntegerIdTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('random_models', static function (Blueprint $table): void {
            $table->unsignedBigInteger('id')->primary();
            $table->string('name');
        });
    }

    public function test_trait_generates_non_incrementing_nine_digit_integer_ids(): void
    {
        $model = RandomModel::query()->create(['name' => 'A']);

        $this->assertFalse($model->getIncrementing());
        $this->assertSame('int', $model->getKeyType());
        $this->assertGreaterThanOrEqual(100_000_000, $model->getKey());
        $this->assertLessThanOrEqual(999_999_999, $model->getKey());
    }

    public function test_trait_keeps_a_manually_assigned_id(): void
    {
        $model = RandomModel::query()->create(['id' => 123_456_789, 'name' => 'B']);

        $this->assertSame(123_456_789, $model->getKey());
    }
}

final class RandomModel extends Model
{
    use HasRandomIntegerId;

    public $timestamps = false;
    protected $table = 'random_models';
    protected $guarded = [];
}
