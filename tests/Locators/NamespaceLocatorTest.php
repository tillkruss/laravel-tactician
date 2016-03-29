<?php

namespace TillKruss\LaravelTactician\Tests\Locators;

use Mockery;
use PHPUnit_Framework_TestCase;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Config\Repository as Configuration;
use League\Tactician\Exception\MissingHandlerException;
use TillKruss\LaravelTactician\Locators\NamespaceLocator;
use TillKruss\LaravelTactician\Tests\Fixtures\Nested\Commands\NestedCommand;
use TillKruss\LaravelTactician\Tests\Fixtures\Nested\Commands\AnotherNestedCommand;
use TillKruss\LaravelTactician\Tests\Fixtures\Nested\Handlers\Commands\NestedCommandHandler;

class NamespaceLocatorTest extends PHPUnit_Framework_TestCase
{
    private $container;

    private $config;

    private $locator;

    private $namespaces = [
        'commands' => 'TillKruss\LaravelTactician\Tests\Fixtures\Nested\Commands',
        'handlers' => 'TillKruss\LaravelTactician\Tests\Fixtures\Nested\Handlers\Commands',
    ];

    protected function setUp()
    {
        $this->container = Mockery::mock(Container::class);
        $this->config = Mockery::mock(Configuration::class);
        $this->locator = new NamespaceLocator($this->container, $this->config);
    }

    public function testHandleIsReturnedForCommand()
    {
        $this->container->shouldReceive('make')->andReturn(new NestedCommandHandler);
        $this->config->shouldReceive('get')->andReturn($this->namespaces);

        $this->assertInstanceOf(
            NestedCommandHandler::class,
            $this->locator->getHandlerForCommand(NestedCommand::class)
        );
    }

    public function testMissingHandlerClassCausesException()
    {
        $this->setExpectedException(MissingHandlerException::class);

        $this->container->shouldReceive('make')->never();
        $this->config->shouldReceive('get')->andReturn($this->namespaces);

        $this->locator->getHandlerForCommand(AnotherNestedCommand::class);
    }
}
