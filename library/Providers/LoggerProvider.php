<?php

namespace Library\Providers;

use Monolog\Handler\SyslogHandler;
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
                $logName = envValue('LOGGER_DEFAULT_FILENAME', 'app');

                /** @var string $logPath */
                $logPath = envValue('LOGGER_DEFAULT_PATH', 'storage/logs');
                
                $logChanel = envValue('LOGGER_CHANEL', 'file');

                $logFile = appPath($logPath) . '/' . $logName . '.log';
                $formatter = new LineFormatter("[%datetime%][%level_name%] %message%\n");
                $logger = new Logger('api-logger');

                $handler = new StreamHandler($logFile, Logger::DEBUG);
                
                switch ($logChanel) {
                    case 'file':
                        $handler = new StreamHandler($logFile, Logger::DEBUG);
                        break;
                    case 'syslog':
                        $handler = new SyslogHandler($logName);
                        break;
                }
                
                $handler->setFormatter($formatter);
                $logger->pushHandler($handler);
                return $logger;
            }
        );
    }
}

