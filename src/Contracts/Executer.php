<?php

namespace TillKruss\LaravelTactician\Contracts;

use ArrayAccess;

interface Executer
{
    /**
     * Executes a command in the command bus.
     *
     * @param  object  $command
     * @return mixed
     */
    public function execute($command);

    /**
     * Marshal a command from the given object and execute it in the command bus.
     *
     * @param  string       $command
     * @param  ArrayAccess  $source
     * @param  array        $extras
     * @return mixed
     */
    public function executeFrom($command, ArrayAccess $source, array $extras = []);

    /**
     * Marshal a command from the given array and execute it in the command bus.
     *
     * @param  string  $command
     * @param  array   $array
     * @return mixed
     */
    public function executeFromArray($command, array $array);
}
