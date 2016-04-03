# Laravel Tactician Usage

To pass commands to the command bus is, you can either use the `Executer` or the `ExecutesCommands` trait.

Both come with the following methods:

- `execute($command, array $middleware = [])`
- `executeFrom($command, ArrayAccess $source, array $extras = [], array $middleware = [])`
- `function executeFromArray($command, array $array, array $middleware = [])`

You'll find examples for all three methods below, but first the basics.


## Execute Command via Executer

```php
use App\Http\Controllers\Controller;
use App\Commands\RegisterUserCommand;
use TillKruss\LaravelTactician\Contracts\Executer;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $command = new RegisterUserCommand(
            $request->get('email'),
            $request->get('password')
        );

        app(Executer::class)->execute($command);
    }
}
```


## Execute Command via Trait

```php
use App\Http\Controllers\Controller;
use App\Commands\RegisterUserCommand;
use TillKruss\LaravelTactician\ExecutesCommands;

class UserController extends Controller
{
    use ExecutesCommands;

    public function store(Request $request)
    {
        $command = new RegisterUserCommand(
            $request->get('email'),
            $request->get('password')
        );

        $this->execute($command);
    }
}
```


## Using `execute()`

...

## Using `executeFrom()`

...

## Using `executeFromArray()`

...
