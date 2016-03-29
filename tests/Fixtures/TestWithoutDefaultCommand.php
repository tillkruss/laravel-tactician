<?php

namespace TillKruss\LaravelTactician\Tests\Fixtures;

class TestWithoutDefaultCommand
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
}
