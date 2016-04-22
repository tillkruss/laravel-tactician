<?php

namespace TillKruss\LaravelTactician;

use App;
use ArrayAccess;
use TillKruss\LaravelTactician\Contracts\Executer as ExecuterContract;

trait ExecutesCommands
{
    /**
     * Executes a command in the command bus.
     *
     * @param  object  $command
     * @return mixed
     */
    protected function execute($command)
    {
        return App::make(ExecuterContract::class)->execute($command);
    }

    /**
     * Marshal a command from the given object and execute it in the command bus.
     *
     * @param  string       $command
     * @param  ArrayAccess  $source
     * @param  array        $extras
     * @return mixed
     */
    protected function executeFrom($command, ArrayAccess $source, array $extras = [])
    {
        return App::make(ExecuterContract::class)->executeFrom($command, $source, $extras);
    }

    /**
     * Marshal a command from the given array and execute it in the command bus.
     *
     * @param  string  $command
     * @param  array   $array
     * @return mixed
     */
    protected function executeFromArray($command, array $array)
    {
        return App::make(ExecuterContract::class)->executeFromArray($command, $array);
    }
}
