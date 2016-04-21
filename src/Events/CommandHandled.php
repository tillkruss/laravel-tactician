<?php

namespace TillKruss\LaravelTactician\Events;

use Illuminate\Queue\SerializesModels;
use League\Tactician\CommandEvents\Event\HasCommand;
use League\Tactician\CommandEvents\Event\CommandEvent;

class CommandHandled implements CommandEvent
{
    use HasCommand, SerializesModels;

    /**
     * Create a new "command handled" event object.
     *
     * @param object  $command
     */
    public function __construct($command)
    {
        $this->command = $command;
    }
}
