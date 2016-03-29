<?php

namespace TillKruss\LaravelTactician\Tests\Middleware;

use Error;
use Mockery;
use stdClass;
use Exception;
use PHPUnit_Framework_TestCase;
use Illuminate\Database\DatabaseManager;
use TillKruss\LaravelTactician\Middleware\TransactionMiddleware;

class TransactionMiddlewareTest extends PHPUnit_Framework_TestCase
{
    private $database;
    private $middleware;

    protected function setUp()
    {
        $this->database = Mockery::mock(DatabaseManager::class);
        $this->middleware = new TransactionMiddleware($this->database);
    }

    public function testCommandSucceedsAndTransactionIsCommitted()
    {
        $this->database->shouldReceive('beginTransaction')->once();
        $this->database->shouldReceive('commit')->once();
        $this->database->shouldReceive('rollBack')->never();

        $executed = 0;
        $next = function () use (&$executed) {
            $executed++;
        };

        $this->middleware->execute(new stdClass, $next);

        $this->assertEquals(1, $executed);
    }

    public function testCommandFailsWithExceptionAndTransactionIsRolledBack()
    {
        $this->database->shouldReceive('beginTransaction')->once();
        $this->database->shouldReceive('commit')->never();
        $this->database->shouldReceive('rollBack')->once();

        $this->setExpectedException(Exception::class, 'Command Failed');

        $next = function () use (&$executed) {
            throw new Exception('Command Failed');
        };

        $this->middleware->execute(new stdClass, $next);
    }

    public function testCommandFailsWithErrorAndTransactionIsRolledBack()
    {
        $this->database->shouldReceive('beginTransaction')->once();
        $this->database->shouldReceive('commit')->never();
        $this->database->shouldReceive('rollBack')->once();

        $this->setExpectedException(Error::class, 'Command Failed');

        $next = function () use (&$executed) {
            throw new Error('Command Failed');
        };

        $this->middleware->execute(new stdClass, $next);
    }
}
