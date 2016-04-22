# Laravel Tactician Usage

Table of Contents:

- [Executing Commands](#executing-commands)
- [Execute Commands via Executer](#execute-commands-via-executer)
- [Execute Commands via Trait](#execute-commands-via-trait)
- [Using `execute()`](#using-execute)
- [Using `executeFrom()`](#using-executefrom)
- [Using `executeFromArray()`](#using-executefromarray)
- [Listening to Command Events](#listening-to-command-events)

## Executing Commands

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


## Listening to Command Events

If the `EventMiddleware` is enabled, it dispatches an event whenever a command is received, handled or fails. You may specify listeners for following events in your `EventServiceProvider`:

- `command.received`
- `command.handled`
- `command.failed`

Event listeners will receive the command event object. You may access the command itself with the `getCommand()` method:

```php
use TillKruss\LaravelTactician\Events\CommandHandled;

class CommandHandledListener
{
    public function handle(CommandHandled $event)
    {
        // $event->getCommand()
    }
}
```

You can also catch an error and prevent it from causing the application to fail:

```php
use App\Exceptions\SomethingWentWrong;
use TillKruss\LaravelTactician\Events\CommandFailed;

class CommandFailedListener
{
    public function handle(CommandFailed $event)
    {
        if ($event->getException() instanceof SomethingWentWrong) {
            $event->catchException();
        }        
    }
}
```
