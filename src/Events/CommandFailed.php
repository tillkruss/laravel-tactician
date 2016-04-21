<?php

namespace TillKruss\LaravelTactician\Events;

use Illuminate\Queue\SerializesModels;
use League\Tactician\CommandEvents\Event\HasCommand;
use League\Tactician\CommandEvents\Event\CommandEvent;

class CommandFailed implements CommandEvent
{
    use HasCommand, SerializesModels;

    /**
     * The exception instance.
     *
     * @var Exception|Throwable
     */
    protected $exception;

    /**
     * Whether the exception is caught, or not.
     *
     * @var boolean
     */
    protected $exceptionCaught = false;

    /**
     * Create a new "command failed" event object.
     *
     * @param object               $command
     * @param Exception|Throwable  $exception
     */
    public function __construct($command, $exception)
    {
        $this->command = $command;
        $this->exception = $exception;
    }

    /**
     * Return the exception instance.
     *
     * @return Exception|Throwable
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Mark the exception as caught.
     */
    public function catchException()
    {
        $this->exceptionCaught = true;
    }

    /**
     * Whether the exception is caught, or not.
     *
     * @return boolean
     */
    public function isExceptionCaught()
    {
        return $this->exceptionCaught;
    }
}
