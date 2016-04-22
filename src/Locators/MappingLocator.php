<?php

namespace TillKruss\LaravelTactician\Locators;

use Illuminate\Contracts\Container\Container;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Exception\MissingHandlerException;
use Illuminate\Contracts\Config\Repository as Configuration;

class MappingLocator implements HandlerLocator
{
    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The configuration instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create a new mapping locator instance.
     *
     * @param \Illuminate\Contracts\Container\Container  $container
     * @param \Illuminate\Contracts\Config\Repository    $config
     */
    public function __construct(Container $container, Configuration $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * Returns the handler bound to the command's class name.
     *
     * @param  string  $command
     * @return object
     *
     * @throws \League\Tactician\Exception\MissingHandlerException
     */
    public function getHandlerForCommand($command)
    {
        $handlers = $this->config->get('tactician.handlers', []);

        if (! isset($handlers[$command]) || ! class_exists($handlers[$command])) {
            throw MissingHandlerException::forCommand($command);
        }

        return $this->container->make($handlers[$command]);
    }
}
