<?php

namespace TillKruss\LaravelTactician\Locators;

use Illuminate\Contracts\Container\Container;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Exception\MissingHandlerException;
use Illuminate\Contracts\Config\Repository as Configuration;

class NamespaceLocator implements HandlerLocator
{
    /**
     * The container instance.
     *
     * @var Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The configuration instance.
     *
     * @var Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create a new namespace locator instance.
     *
     * @param Illuminate\Contracts\Container\Container  $container
     * @param Illuminate\Contracts\Config\Repository    $config
     */
    public function __construct(Container $container, Configuration $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * Returns the handler located in the configured namespace for given command.
     *
     * @param  string  $command
     * @return object
     *
     * @throws League\Tactician\Exception\MissingHandlerException
     */
    public function getHandlerForCommand($command)
    {
        $namespaces = $this->config->get('tactician.namespaces', []);

        $handler = sprintf(
            '%s\\%sHandler',
            $namespaces['handlers'],
            trim(str_ireplace($namespaces['commands'], '', $command), '\\')
        );

        if (! class_exists($handler)) {
            throw MissingHandlerException::forCommand($handler);
        }

        return $this->container->make($handler);
    }
}
