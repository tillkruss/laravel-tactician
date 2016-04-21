<?php

namespace TillKruss\LaravelTactician\Middleware;

use Exception;
use Throwable;
use League\Tactician\Middleware;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Foundation\Application;

class LoggerMiddleware implements Middleware
{
    /**
     * The application instance.
     *
     * @var Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The logger instance.
     *
     * @var Illuminate\Contracts\Logging\Log
     */
    protected $logger;

    /**
     * Create a new logger middleware.
     *
     * @param Illuminate\Contracts\Logging\Log   $logger
     * @param Illuminate\Foundation\Application  $app
     */
    public function __construct(Log $logger, Application $app)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

    /**
     * Write a message to the log whenever a command is received, handled or fails.
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
        $levels = $this->app->config->get('tactician.log.levels');
        $formatter = $this->app->make($this->app->config->get('tactician.log.formatter'));

        $this->log($levels['received'], $formatter->commandReceived($command));

        try {
            $returnValue = $next($command);
        } catch (Exception $exception) {
            $this->log($levels['failed'], $formatter->commandFailed($command, $exception));

            throw $exception;
        } catch (Throwable $exception) {
            $this->log($levels['failed'], $formatter->commandFailed($command, $exception));

            throw $exception;
        }

        $this->log($levels['handled'], $formatter->commandHandled($command));

        return $returnValue;
    }

    /**
     * Passes message and log level to logger instance.
     *
     * @param  string       $logLevel
     * @param  string|null  $message
     */
    protected function log($logLevel, $message)
    {
        if (! is_null($message)) {
            $this->logger->log($logLevel, $message);
        }
    }
}
