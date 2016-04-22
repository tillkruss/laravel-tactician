<?php

namespace TillKruss\LaravelTactician\Tests\Middleware;

use Mockery;
use Exception;
use PHPUnit_Framework_TestCase;
use Illuminate\Contracts\Events\Dispatcher;
use TillKruss\LaravelTactician\Events\CommandFailed;
use TillKruss\LaravelTactician\Events\CommandHandled;
use TillKruss\LaravelTactician\Events\CommandReceived;
use TillKruss\LaravelTactician\Middleware\EventMiddleware;
use TillKruss\LaravelTactician\Tests\Fixtures\TestCommand;

class EventMiddlewareTest extends PHPUnit_Framework_TestCase
{
    private $dispatcher;
    private $middleware;

    protected function setUp()
    {
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->middleware = new EventMiddleware($this->dispatcher);
    }

    public function testSuccessEventsAreDispatched()
    {
        $this->dispatcher->shouldReceive('fire')->once()->with(
            'command.received', Mockery::type(CommandReceived::class)
        );
        $this->dispatcher->shouldReceive('fire')->once()->with(
            'command.handled', Mockery::type(CommandHandled::class)
        );

        $this->middleware->execute(new TestCommand, function () {});
    }

    public function testFailureEventsAreDispatched()
    {
        $this->dispatcher->shouldReceive('fire')->once()->with(
            'command.received', Mockery::type(CommandReceived::class)
        );
        $this->dispatcher->shouldReceive('fire')->once()->with(
            'command.failed', Mockery::type(CommandFailed::class)
        );

        $this->setExpectedException(Exception::class, 'Command Failed');

        $next = function () use (&$executed) {
            throw new Exception('Command Failed');
        };

        $this->middleware->execute(new TestCommand, $next);
    }

    public function testFailureEventExceptionsCanBeCaught()
    {
        $this->dispatcher->shouldReceive('fire')->with(
            'command.failed',
            Mockery::on(function ($event) {
                if ($event instanceof CommandFailed) {
                    $event->catchException();
                }

                return true;
            })
        );

        $next = function () use (&$executed) {
            throw new Exception('Command Failed');
        };

        $this->middleware->execute(new TestCommand, $next);
    }

    public function testNextCallableIsInvoked()
    {
        $this->dispatcher->shouldIgnoreMissing();

        $sentCommand = new TestCommand;
        $receivedSameCommand = false;

        $next = function ($receivedCommand) use (&$receivedSameCommand, $sentCommand) {
            $receivedSameCommand = ($receivedCommand === $sentCommand);
        };

        $this->middleware->execute($sentCommand, $next);

        $this->assertTrue($receivedSameCommand);
    }
}
