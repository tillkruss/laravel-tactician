<?php

namespace TillKruss\LaravelTactician\Middleware;

use Exception;
use Throwable;
use League\Tactician\Middleware;
use Illuminate\Database\DatabaseManager;

class TransactionMiddleware implements Middleware
{
    /**
     * The database manager instance.
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $database;

    /**
     * Create a new transaction middleware.
     *
     * @param \Illuminate\Database\DatabaseManager  $database
     */
    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }

    /**
     * Wrap a command execution in a database transaction.
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
        $this->database->beginTransaction();

        try {
            $returnValue = $next($command);

            $this->database->commit();
        } catch (Exception $exception) {
            $this->database->rollBack();

            throw $exception;
        } catch (Throwable $exception) {
            $this->database->rollBack();

            throw $exception;
        }

        return $returnValue;
    }
}
