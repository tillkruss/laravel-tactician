<?php

namespace TillKruss\LaravelTactician\Tests\Locators;

use Mockery;
use PHPUnit_Framework_TestCase;
use Illuminate\Contracts\Container\Container;
use League\Tactician\Exception\MissingHandlerException;
use TillKruss\LaravelTactician\Locators\AdjacentLocator;
use TillKruss\LaravelTactician\Tests\Fixtures\TestCommand;
use TillKruss\LaravelTactician\Tests\Fixtures\TestCommandHandler;
use TillKruss\LaravelTactician\Tests\Fixtures\PlainCommand;

class AdjacentLocatorTest extends PHPUnit_Framework_TestCase
{
    private $container;
    private $locator;

    protected function setUp()
    {
        $this->container = Mockery::mock(Container::class);
        $this->locator = new AdjacentLocator($this->container);
    }

    public function testHandleIsReturnedForCommand()
    {
        $this->container->shouldReceive('make')->andReturn(new TestCommandHandler);

        $this->assertInstanceOf(
            TestCommandHandler::class,
            $this->locator->getHandlerForCommand(TestCommand::class)
        );
    }

    public function testMissingHandlerClassCausesException()
    {
        $this->setExpectedException(MissingHandlerException::class);

        $this->container->shouldReceive('make')->never();

        $this->locator->getHandlerForCommand(PlainCommand::class);
    }
}
