<?php

namespace TillKruss\LaravelTactician\Middleware;

use Exception;
use Throwable;
use League\Tactician\Middleware;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Foundation\Application;

class LoggerMiddleware implements Middleware
{
    protected $app;
    protected $logger;

    public function __construct(Log $logger, Application $app)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

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

    protected function log($logLevel, $message)
    {
        if (! is_null($message)) {
            $this->logger->log($logLevel, $message);
        }
    }
}
