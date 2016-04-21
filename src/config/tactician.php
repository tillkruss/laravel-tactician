<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Handler Method Inflector
    |--------------------------------------------------------------------------
    |
    | First, you may specify the inflector class that's used to determine
    | what method you want to call on your command handlers when they
    | receive a command.
    |
    */

    'inflector' => League\Tactician\Handler\MethodNameInflector\HandleInflector::class,

    /*
    |--------------------------------------------------------------------------
    | Command Name Extractor
    |--------------------------------------------------------------------------
    |
    | Next, you may define the extractor class used to identify which
    | command we’re dealing with. The class accepts an incoming
    | command and returns a string name for it.
    |
    */

    'extractor' => League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor::class,

    /*
    |--------------------------------------------------------------------------
    | Command Handler Locator
    |--------------------------------------------------------------------------
    |
    | Then, you may set the which locator class to use to locate the actual
    | command handler class based on the command's name received from
    | the command name extractor.
    |
    */

    'locator' => TillKruss\LaravelTactician\Locators\AdjacentLocator::class,

    /*
    |--------------------------------------------------------------------------
    | Middleware Stack
    |--------------------------------------------------------------------------
    |
    | In this list you may specify the global middleware stack that's
    | injected into the command bus instance to add behavior.
    | Middleware are executed in sequence.
    |
    */

    'middleware' => [
        // League\Tactician\Plugins\LockingMiddleware::class,
        // TillKruss\LaravelTactician\Middleware\TransactionMiddleware::class,
        // TillKruss\LaravelTactician\Middleware\LoggerMiddleware::class,
        League\Tactician\Handler\CommandHandlerMiddleware::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    | Here you may define the namespaces used by the namespace locator.
    | These namespaces act as the base path for locating of commands
    | and command handlers used in the app.
    |
    */

    'namespaces' => [
        'commands' => 'App\Commands',
        'handlers' => 'App\Handlers\Commands',
    ],

    /*
    |--------------------------------------------------------------------------
    | Command Handler Mappings
    |--------------------------------------------------------------------------
    |
    | Here you may specify the command handler mappings for the mappings
    | locator. The handlers list should contains an array of all
    | commands (keys) and their handlers (values).
    |
    */

    'handlers' => [
        'App\Commands\SomeCommand' => 'App\Handlers\Commands\SomeCommandHandler',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logger Middleware
    |--------------------------------------------------------------------------
    |
    | Here you may set the formatter that is responsible for converting
    | the command into a loggable message and the log levels used
    | for received, handled and failed commands.
    |
    | Supported: "debug", "info", "notice", "warning",
    |            "error", "critical", "alert", "emergency"
    |
    */

    'log' => [

        'formatter' => League\Tactician\Logger\Formatter\ClassNameFormatter::class,

        'levels' => [
            'received' => 'debug',
            'handled' => 'debug',
            'failed' => 'error',
        ],

    ],

];
