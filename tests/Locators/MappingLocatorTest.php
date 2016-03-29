<?php

namespace TillKruss\LaravelTactician\Tests\Locators;

use Mockery;
use PHPUnit_Framework_TestCase;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Config\Repository as Configuration;
use League\Tactician\Exception\MissingHandlerException;
use TillKruss\LaravelTactician\Locators\MappingLocator;
use TillKruss\LaravelTactician\Tests\Fixtures\TestCommand;
use TillKruss\LaravelTactician\Tests\Fixtures\TestCommandHandler;

class MappingLocatorTest extends PHPUnit_Framework_TestCase
{
    private $container;
    private $config;
    private $locator;

    protected function setUp()
    {
        $this->container = Mockery::mock(Container::class);
        $this->config = Mockery::mock(Configuration::class);
        $this->locator = new MappingLocator($this->container, $this->config);
    }

    public function testHandleIsReturnedForCommand()
    {
        $this->container->shouldReceive('make')->andReturn(new TestCommandHandler);

        $this->config->shouldReceive('get')->andReturn([
            TestCommand::class => TestCommandHandler::class,
        ]);

        $this->assertInstanceOf(
            TestCommandHandler::class,
            $this->locator->getHandlerForCommand(TestCommand::class)
        );
    }

    public function testMissingHandlerMappingCausesException()
    {
        $this->setExpectedException(MissingHandlerException::class);

        $this->container->shouldReceive('make')->never();
        $this->config->shouldReceive('get')->andReturn();

        $this->locator->getHandlerForCommand(TestCommand::class);
    }

    public function testMissingHandlerClassCausesException()
    {
        $this->setExpectedException(MissingHandlerException::class);

        $this->container->shouldReceive('make')->never();

        $this->config->shouldReceive('get')->andReturn([
            TestCommand::class => 'InvalidClass',
        ]);

        $this->locator->getHandlerForCommand(TestCommand::class);
    }
}
