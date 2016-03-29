<?php

namespace TillKruss\LaravelTactician\Exceptions;

use OutOfBoundsException;

class CanNotMapParameterValue extends OutOfBoundsException
{
    /**
     * The command's class name.
     *
     * @var string
     */
    private $command;

    /**
     * The parameter's name.
     *
     * @var string
     */
    private $parameter;

    /**
     * Creates a new exception.
     *
     * @param  string  $command
     * @param  string  $parameter
     * @return static
     */
    public static function forCommand($command, $parameter)
    {
        $exception = new static("Could not map parameter [{$parameter}] to command [{$command}].");
        $exception->command = $command;
        $exception->parameter = $parameter;

        return $exception;
    }

    /**
     * Returns class name of the command.
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Returns the name of the parameter.
     *
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }
}
