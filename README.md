# Nuewire Support

Shared utilities for Nuewire Laravel packages.

## Random integer IDs

```php
use Illuminate\Database\Eloquent\Model;
use Nuewire\Support\Concerns\HasRandomIntegerId;

class Customer extends Model
{
    use HasRandomIntegerId;
}
```

New records receive a unique integer ID between `100000000` and `999999999`.
The trait sets the Eloquent model to non-incrementing integer keys.

Use a non-incrementing primary key in new migrations:

```php
$table->unsignedBigInteger('id')->primary();
```

Override the range for one model when needed:

```php
protected function nuewireRandomIntegerIdMinimum(): int
{
    return 200000000;
}
```

Global defaults can be published with:

```bash
php artisan vendor:publish --tag=nuewire-support-config
```
