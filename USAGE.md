# Laravel Tactician Usage

To pass commands to the command bus is, you can either use the `Executer` or the `ExecutesCommands` trait.

Both come with the following methods:

```php
public function execute($command);

public function executeFrom($command, ArrayAccess $source, array $extras = []);

public function executeFromArray($command, array $array);
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
public function execute($command);
```

The `execute()` method takes only one argument, the command object itself:

```php
class UserController extends Controller
{
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


## Using `executeFrom()`

```php
public function executeFrom($command, ArrayAccess $source, array $extras = []);
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


## Using `executeFromArray()`

```php
public function executeFromArray($command, array $array);
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
