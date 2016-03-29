<?php

namespace TillKruss\LaravelTactician\Tests\Fixtures\Nested\Commands;

class NestedCommand
{
    public $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }
}
