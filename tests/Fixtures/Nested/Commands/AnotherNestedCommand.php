<?php

namespace TillKruss\LaravelTactician\Tests\Fixtures\Nested\Commands;

class AnotherNestedCommand
{
    public $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }
}
