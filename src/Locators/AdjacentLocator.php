<?php

namespace TillKruss\LaravelTactician\Locators;

use Illuminate\Contracts\Container\Container;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Exception\MissingHandlerException;

class AdjacentLocator implements HandlerLocator
{
    /**
     * The container instance.
     *
     * @var Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Create a new adjacent locator instance.
     *
     * @param Illuminate\Contracts\Container\Container  $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Returns the handler located in the same directory for a specified command.
     *
     * @param  string  $command
     * @return object
     *
     * @throws League\Tactician\Exception\MissingHandlerException
     */
    public function getHandlerForCommand($command)
    {
        $handler = substr_replace($command, 'CommandHandler', strrpos($command, 'Command'));

        if (! class_exists($handler)) {
            throw MissingHandlerException::forCommand($command);
        }

        return $this->container->make($handler);
    }
}
