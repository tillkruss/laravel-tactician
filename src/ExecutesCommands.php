<?php

namespace TillKruss\LaravelTactician;

use App;
use ArrayAccess;
use TillKruss\LaravelTactician\Contracts\Executer;

trait ExecutesCommands
{
    /**
     * Executes a command in the command bus.
     *
     * @param  object  $command
     * @param  array   $middleware
     * @return mixed
     */
    protected function execute($command, array $middleware = [])
    {
        return App::make(Executer::class)->execute($command, $middleware);
    }

    /**
     * Marshal a command from the given object and execute it in the command bus.
     *
     * @param  string       $command
     * @param  ArrayAccess  $source
     * @param  array        $extras
     * @param  array        $middleware
     * @return mixed
     */
    protected function executeFrom($command, ArrayAccess $source, array $extras = [], array $middleware = [])
    {
        return App::make(Executer::class)->executeFrom($command, $source, $extras, $middleware);
    }

    /**
     * Marshal a command from the given array and execute it in the command bus.
     *
     * @param  string  $command
     * @param  array   $array
     * @param  array   $middleware
     * @return mixed
     */
    protected function executeFromArray($command, array $array, array $middleware = [])
    {
        return App::make(Executer::class)->executeFromArray($command, $array, $middleware);
    }
}
