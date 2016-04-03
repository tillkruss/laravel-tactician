# Laravel Tactician Usage

To pass commands to the command bus is, you can either use the `Executer` or the `ExecutesCommands` trait.

Both come with the following methods:

```php
public function execute($command, array $middleware = []);

public function executeFrom($command, ArrayAccess $source, array $extras = [], array $middleware = []);

public function executeFromArray($command, array $array, array $middleware = []);
```

You can find examples for all three methods below, but first the basics of executing commands.


## Execute Commands via Executer

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


## Execute Commands via Trait

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

```php
public function execute($command, array $middleware = []);
```

The `execute()` method takes two arguments. The first is the command object itself and the second is an _optional_ middleware stack:

```php
class UserController extends Controller
{
    public function store(Request $request)
    {
        $command = new RegisterUserCommand(
            $request->get('email'),
            $request->get('password')
        );

        $middleware = new TransactionMiddleware;

        $this->execute($command, [$middleware]);
    }
}
```


## Using `executeFrom()`

```php
public function executeFrom($command, ArrayAccess $source, array $extras = [], array $middleware = []);
```

The `executeFrom()` method creates a new command object from the given class name and injects the command’s properties from the given array accessible source:

```php
class UserController extends Controller
{
    public function store(Request $request)
    {
        $this->executeFrom(RegisterUserCommand::class, $request);
    }
}
```

Additionally, you may pass in (or override) additional properties to the command with the third argument:

```php
class UserController extends Controller
{
    public function store(Request $request)
    {
        $extras = ['registered_at' => Carbon::now()];

        $this->executeFrom(RegisterUserCommand::class, $request, $extras);
    }
}
```

The `executeFrom()` method accepts an _optional_ middleware stack as fourth argument.


## Using `executeFromArray()`

```php
public function executeFromArray($command, array $array, array $middleware = []);
```

The `executeFromArray()` method creates a new command object from the given class name and injects the command’s properties from the given array:

```php
class UserController extends Controller
{
    public function store(Request $request)
    {
        $array = $request->only(['email', 'password']);

        $this->executeFromArray(RegisterUserCommand::class, $array);
    }
}
```

The `executeFromArray()` method accepts an _optional_ middleware stack as third argument.
