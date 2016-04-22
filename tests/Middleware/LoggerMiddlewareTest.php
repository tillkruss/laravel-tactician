<?php

namespace TillKruss\LaravelTactician\Tests\Middleware;

use Mockery;
use Exception;
use PHPUnit_Framework_TestCase;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Foundation\Application;
use League\Tactician\Logger\Formatter\ClassNameFormatter;
use TillKruss\LaravelTactician\Tests\Fixtures\TestCommand;
use TillKruss\LaravelTactician\Middleware\LoggerMiddleware;
use Illuminate\Contracts\Config\Repository as Configuration;

class LoggerMiddlewareTest extends PHPUnit_Framework_TestCase
{
    private $app;
    private $logger;
    private $formatter;
    private $middlware;

    private $levels = [
        'received' => 'debug',
        'handled' => 'debug',
        'failed' => 'error',
    ];

    protected function setUp()
    {
        $this->app = Mockery::mock(Application::class);
        $this->app->config = Mockery::mock(Configuration::class);
        $this->logger = Mockery::mock(Log::class);
        $this->formatter = Mockery::mock(ClassNameFormatter::class);
        $this->middleware = new LoggerMiddleware($this->logger, $this->app);

        $this->app->shouldReceive('make')->andReturn($this->formatter);
        $this->app->config->shouldReceive('get')->with('tactician.log.formatter');
    }

    public function testSuccessMessagesAreLogged()
    {
        $command = new TestCommand;

        $this->app->config->shouldReceive('get')->with('tactician.log.levels')->andReturn($this->levels);

        $this->formatter->shouldReceive('commandReceived')->with($command)->once()->andReturn('received');
        $this->formatter->shouldReceive('commandHandled')->with($command)->once()->andReturn('handled');

        $this->logger->shouldReceive('log')->with('debug', 'received')->once();
        $this->logger->shouldReceive('log')->with('debug', 'handled')->once();

        $this->middleware->execute($command, function () {});
    }

    public function testFailureMessagesAreLogged()
    {
        $command = new TestCommand;
        $exception = new Exception('Command Failed');

        $this->app->config->shouldReceive('get')->with('tactician.log.levels')->andReturn($this->levels);

        $this->formatter->shouldReceive('commandReceived')->with($command)->once()->andReturn('received');
        $this->formatter->shouldReceive('commandFailed')->with($command, $exception)->once()->andReturn('failed');

        $this->logger->shouldReceive('log')->with('debug', 'received')->once();
        $this->logger->shouldReceive('log')->with('error', 'failed')->once();

        $this->setExpectedException(Exception::class, 'Command Failed');

        $this->middleware->execute(
            $command,
            function () use ($exception) {
                throw $exception;
            }
        );
    }

    public function testNextCallableIsInvoked()
    {
        $this->logger->shouldIgnoreMissing();
        $this->formatter->shouldIgnoreMissing();
        $this->app->config->shouldReceive('get')->with('tactician.log.levels')->andReturn($this->levels);

        $sentCommand = new TestCommand;
        $receivedSameCommand = false;

        $next = function ($receivedCommand) use (&$receivedSameCommand, $sentCommand) {
            $receivedSameCommand = ($receivedCommand === $sentCommand);
        };

        $this->middleware->execute($sentCommand, $next);

        $this->assertTrue($receivedSameCommand);
    }

    public function testNullMessagesAreNotLogged()
    {
        $this->app->config->shouldReceive('get')->with('tactician.log.levels')->andReturn($this->levels);

        $this->formatter->shouldReceive('commandReceived')->andReturnNull();
        $this->formatter->shouldReceive('commandHandled')->andReturn('handled');

        $this->logger->shouldReceive('log')->with('debug', 'handled');

        $this->middleware->execute(new TestCommand, function () {});
    }

    public function testLogLevelsCanBeCustomized()
    {
        $this->app->config->shouldReceive('get')->with('tactician.log.levels')->andReturn([
            'received' => 'info',
            'handled' => 'critical',
        ]);

        $this->formatter->shouldReceive('commandReceived')->andReturn('received');
        $this->formatter->shouldReceive('commandHandled')->andReturn('handled');

        $this->logger->shouldReceive('log')->with('info', 'received');
        $this->logger->shouldReceive('log')->with('critical', 'handled');

        $this->middleware->execute(new TestCommand, function () {});
    }

    public function testErrorLogLevelCanBeCustomized()
    {
        $this->app->config->shouldReceive('get')->with('tactician.log.levels')->andReturn([
            'received' => 'info',
            'failed' => 'emergency',
        ]);

        $this->formatter->shouldReceive('commandReceived')->andReturn('received');
        $this->formatter->shouldReceive('commandFailed')->andReturn('failed');

        $this->logger->shouldReceive('log')->with('info', 'received');
        $this->logger->shouldReceive('log')->with('emergency', 'failed');

        $next = function () use (&$executed) {
            throw new Exception('Command Failed');
        };

        $this->setExpectedException(Exception::class, 'Command Failed');

        $this->middleware->execute(new TestCommand, $next);
    }
}
