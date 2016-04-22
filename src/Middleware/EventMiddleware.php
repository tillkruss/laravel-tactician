<?php

namespace TillKruss\LaravelTactician\Middleware;

use Exception;
use Throwable;
use League\Tactician\Middleware;
use Illuminate\Contracts\Events\Dispatcher;
use TillKruss\LaravelTactician\Events\CommandFailed;
use TillKruss\LaravelTactician\Events\CommandHandled;
use TillKruss\LaravelTactician\Events\CommandReceived;

class EventMiddleware implements Middleware
{
    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * Create a new command events middleware.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dispatch an event whenever a command is received, handled or fails.
     *
     * @param  object    $command
     * @param  callable  $next
     * @return mixed
     *
     * @throws Exception
     * @throws Throwable
     */
    public function execute($command, callable $next)
    {
        try {
            $this->dispatcher->fire(new CommandReceived($command));

            $returnValue = $next($command);

            $this->dispatcher->fire(new CommandHandled($command));

            return $returnValue;
        } catch (Exception $exception) {
            $event = new CommandFailed($command, $exception);

            $this->dispatcher->fire($event);

            if (! $event->isExceptionCaught()) {
                throw $exception;
            }
        } catch (Throwable $exception) {
            $event = new CommandFailed($command, $exception);

            $this->dispatcher->fire($event);

            if (! $event->isExceptionCaught()) {
                throw $exception;
            }
        }
    }
}
