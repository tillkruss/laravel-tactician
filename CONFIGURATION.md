# Laravel Tactician Configuration

All configuration options can be found in the `tactician.php` file, located in your `config` directory.

## Handler Method Inflector

The `inflector` option specifies the class used determine what method you want to call on your command handlers when they receive a command. For more information, please refer to the [Tactician documentation](http://tactician.thephpleague.com/tweaking-tactician/#handler-method).

Tactician comes bundled with these inflector classes:

- `HandleInflector` __(default)__
- `HandleClassNameInflector`
- `HandleClassNameWithoutSuffixInflector`
- `InvokeInflector`

You may define your own inflector class, just be sure to implement the `MethodNameInflector` interface.


## Command Name Extractor

The `extractor` option specifies the class used identify which command we’re dealing with. The class accepts an incoming command and returns a string name for it. For more information, please refer to the [Tactician documentation](http://tactician.thephpleague.com/tweaking-tactician/#command-naming).

By default the `ClassNameExtractor` is used. You may define your own inflector class, just be sure to implement the `CommandNameExtractor` interface.


## Command Handler Locator

The `locator` option specifies which locator class to use to locate the actual command handler class based on the command’s name received from the command name extractor. For more information, please refer to the [Tactician documentation](http://tactician.thephpleague.com/tweaking-tactician/#loading-your-handlers).

This packages comes 3 bundled locator classes.

### Adjacent Locator

The `AdjacentLocator` returns handlers located in the same directory:

```php
Acme\RegisterUserCommand => Acme\RegisterUserCommandHandler
Acme\Foobar\RegisterUserCommand => Acme\Foobar\RegisterUserCommandHandler
```

### Namespace Locator

The `NamespaceLocator` returns handlers located in the configured namespace. See `namespaces` option.

By default commands are located in `App\Commands` and handlers are located in `App\Handlers\Commands`:

```php
App\Commands\RegisterUserCommand => App\Handlers\Commands\RegisterUserCommandHandler
```

### Mapping Locator

The `MappingLocator` returns handlers based on the command’s class name. See `handlers` option.

```php
Acme\Foo\Bar\RegisterUserCommand => Acme\Foobar\RegisterUserHandler',
```


## Middleware Stack

The `middleware` option specifies the global middleware stack that’s automatically injected into the command bus.

By default only Tactician’s `CommandHandlerMiddleware` is enabled to locate the appropriate handler class and pass the command to it.

You may enable the following bundled middleware, or add your own custom middleware, just be sure to implement the `Middleware` interface. For more information, please refer to the [Tactician documentation](http://tactician.thephpleague.com/middleware/).

### Locking Middleware

Tactician’s bundled `LockingMiddleware` blocks any commands from running inside commands. If a command is already being executed and another command comes in, this middleware will queue it in-memory until the first command completes.

### Transaction Middleware

The `TransactionMiddleware` executes each command in a separate database transaction. It will start a transaction before each command begins. If the command is successful, it will commit the transaction. If an `Exception` or `Throwable` is raised, it rolls back the transaction and rethrows the exception.


## Namespaces

The `namespaces` option defines the namespaces used by the `NamespaceLocator`. The namespaces act as the base path for locating of commands.


## Command Handler Mappings

The `handlers` option is a simple list of commands _(keys)_ and their handlers _(values)_ used by the `MappingLocator`:

```php
'handlers' => [
    App\Commands\RegisterUserCommand::class => App\Handlers\RegisterUserHandler::class,
    // ...
],
```
