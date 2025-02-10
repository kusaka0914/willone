<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function isLocal(): bool
    {
        return config('app.env') === 'local';
    }

    protected function isDev(): bool
    {
        return config('app.env') === 'development';
    }

}
