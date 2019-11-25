<?php

namespace Library\Providers;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

use function Library\Core\envValue;
use function Library\Core\appPath;

class LoggerProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared(
            'logger',
            function () {
                /** @var string $logName */
                $logName = envValue('LOGGER_DEFAULT_FILENAME', 'api.log');

                /** @var string $logPath */
                $logPath = envValue('LOGGER_DEFAULT_PATH', 'storage/logs');

                $logFile = appPath($logPath) . '/' . $logName . '.log';
                $formatter = new LineFormatter("[%datetime%][%level_name%] %message%\n");
                $logger = new Logger('api-logger');
                $handler = new StreamHandler($logFile, Logger::DEBUG);
                $handler->setFormatter($formatter);
                $logger->pushHandler($handler);
                return $logger;
            }
        );
    }
}

