<?php

namespace TillKruss\LaravelTactician;

use ArrayAccess;
use ReflectionClass;
use ReflectionParameter;
use League\Tactician\CommandBus;
use Illuminate\Support\Collection;
use TillKruss\LaravelTactician\Exceptions\CanNotMapParameterValue;
use TillKruss\LaravelTactician\Contracts\Executer as ExecuterContract;

class Executer implements ExecuterContract
{
    /**
     * The command bus instance.
     *
     * @var League\Tactician\CommandBus
     */
    protected $bus;

    /**
     * Create a new command bus executer.
     *
     * @param League\Tactician\CommandBus  $bus
     */
    public function __construct(CommandBus $bus)
    {
        $this->bus = $bus;
    }

    /**
     * Executes a command in the command bus.
     *
     * @param  object  $command
     * @return mixed
     */
    public function execute($command)
    {
        return $this->bus->handle($command);
    }

    /**
     * Marshal a command from the given class name and execute it in the command bus.
     *
     * @param  string       $command
     * @param  ArrayAccess  $source
     * @param  array        $extras
     * @return mixed
     */
    public function executeFrom($command, ArrayAccess $source, array $extras = [])
    {
        return $this->execute($this->marshal($command, $source, $extras));
    }

    /**
     * Marshal a command from the given class name and execute it in the command bus.
     *
     * @param  string  $command
     * @param  array   $array
     * @return mixed
     */
    public function executeFromArray($command, array $array)
    {
        return $this->execute($this->marshalFromArray($command, $array));
    }

    /**
     * Marshal a command from the given array accessible object.
     *
     * @param  string       $command
     * @param  ArrayAccess  $source
     * @param  array        $extras
     * @return object
     */
    protected function marshal($command, ArrayAccess $source, array $extras = [])
    {
        $injected = [];
        $reflection = new ReflectionClass($command);

        if ($constructor = $reflection->getConstructor()) {
            $injected = array_map(function ($parameter) use ($command, $source, $extras) {
                return $this->getParameterValueForCommand($command, $source, $parameter, $extras);
            }, $constructor->getParameters());
        }

        return $reflection->newInstanceArgs($injected);
    }

    /**
     * Marshal a command from the given array.
     *
     * @param  string  $command
     * @param  array   $array
     * @return object
     */
    protected function marshalFromArray($command, array $array)
    {
        return $this->marshal($command, new Collection, $array);
    }

    /**
     * Get a parameter value for a marshalled command.
     *
     * @param  string              $command
     * @param  ArrayAccess         $source
     * @param  ReflectionParameter $parameter
     * @param  array               $extras
     * @return mixed
     *
     * @throws TillKruss\LaravelTactician\Exceptions\CanNotMapParameterValue
     */
    protected function getParameterValueForCommand(
        $command,
        ArrayAccess $source,
        ReflectionParameter $parameter,
        array $extras = []
    ) {
        if (array_key_exists($parameter->name, $extras)) {
            return $extras[$parameter->name];
        }

        if (isset($source[$parameter->name])) {
            return $source[$parameter->name];
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw CanNotMapParameterValue::forCommand($command, $parameter->name);
    }
}
