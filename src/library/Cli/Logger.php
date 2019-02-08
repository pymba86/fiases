<?php

namespace Library\Cli;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class Logger extends AbstractLogger
{

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = array())
    {
        if ($level === LogLevel::ERROR) {
            $message = "\033[31m{$message}\033[0m";
        } else {
            $message = "\033[32m{$message}\033[0m";
        }
        echo $message . "\r\n";
    }
}