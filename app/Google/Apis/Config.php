<?php

namespace App\Google\Apis;

class Config
{
    public $api;

    public function __construct()
    {
        $this->api = config('google.api');
    }
}
