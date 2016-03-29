<?php

namespace TillKruss\LaravelTactician\Tests;

use Mockery;
use PHPUnit_Framework_TestCase;
use League\Tactician\CommandBus;
use League\Tactician\Middleware;
use Illuminate\Support\Collection;
use TillKruss\LaravelTactician\Executer;
use Illuminate\Contracts\Container\Container;
use TillKruss\LaravelTactician\Tests\Fixtures\TestCommand;
use TillKruss\LaravelTactician\Exceptions\CanNotMapParameterValue;
use TillKruss\LaravelTactician\Tests\Fixtures\TestWithoutDefaultCommand;

class ExecuterTest extends PHPUnit_Framework_TestCase
{
    private $container;
    private $executer;

    public function setUp()
    {
        $this->container = Mockery::mock(Container::class);
        $this->bus = Mockery::mock(CommandBus::class);
        $this->executer = new Executer($this->container);
    }

    // ToDo: testBasicExecutionOfCommandsToHandlers
    public function testExecutePassesCommandsToCommandBusHandler()
    {
        $this->bus->shouldReceive('handle')->andReturn('foobar');
        $this->container->shouldReceive('make')->andReturn($this->bus);

        $this->assertEquals(
            'foobar',
            $this->executer->execute(new TestCommand)
        );
    }

    public function testMiddlewareAreExecutedAndReturnValuesAreRespected()
    {
        $this->container->shouldReceive('make')->andReturnUsing(function ($class, $middleware) {
            return new $class($middleware);
        });

        $middleware = Mockery::mock(Middleware::class);
        $middleware->shouldReceive('execute')->andReturn('foobar');

        $this->assertEquals(
            'foobar',
            $this->executer->execute(new TestCommand, [$middleware])
        );
    }

    public function testExecutingFromArray()
    {
        $this->bus->shouldReceive('handle')->andReturnUsing(function ($command) {
            return $command->data;
        });

        $this->container->shouldReceive('make')->andReturn($this->bus);

        $this->assertEquals(
            'foobar',
            $this->executer->executeFromArray(TestCommand::class, ['data' => 'foobar'])
        );
    }

    public function testExecutingFromArrayAccessObject()
    {
        $this->bus->shouldReceive('handle')->andReturnUsing(function ($command) {
            return $command->data;
        });

        $this->container->shouldReceive('make')->andReturn($this->bus);

        $object = new Collection(['data' => 'foobar']);

        $this->assertEquals(
            'foobar',
            $this->executer->executeFrom(TestCommand::class, $object)
        );
    }

    public function testExecutingFromArrayAccessObjectWithExtras()
    {
        $this->bus->shouldReceive('handle')->andReturnUsing(function ($command) {
            return $command->data;
        });

        $this->container->shouldReceive('make')->andReturn($this->bus);

        $object = new Collection(['data' => 'foo']);

        $this->assertEquals(
            'bar',
            $this->executer->executeFrom(TestCommand::class, $object, ['data' => 'bar'])
        );
    }

    public function testMissingParameterValueForCommandCausesException()
    {
        $this->setExpectedException(CanNotMapParameterValue::class);

        $this->container->shouldReceive('make')->andReturn($this->bus);

        $this->executer->executeFrom(TestWithoutDefaultCommand::class, new Collection);
    }
}
