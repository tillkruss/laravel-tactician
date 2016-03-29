<?php

namespace TillKruss\LaravelTactician\Tests\Fixtures;

class TestCommand
{
    public $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }
}
