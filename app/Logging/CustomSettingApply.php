<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Level;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;

class CustomSettingApply
{
    public function __invoke(Logger $logger)
    {
        $introspectionProcessor = new IntrospectionProcessor(Level::Debug, ['Illuminate\\']);
        $webProcessor = new WebProcessor();

        $logger->pushProcessor($introspectionProcessor);
        $logger->pushProcessor($webProcessor);
        $logger->pushProcessor(function ($record) {
            $record['extra']['platform'] = 'laravel';
            return $record;
        });

        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof StreamHandler) {
                $handler->setFormatter(new JsonFormatter());
            }
        }
    }
}
