<?php
namespace Library\Providers;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

use function Library\Core\envValue;
use function Library\Core\appPath;

class ConsoleLogProvider implements ServiceProviderInterface
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

                $logger = new Logger('cli-logger');

                // format
                $formatter = new LineFormatter("[%datetime%][%level_name%] %message%\n");

                // file handler
                $logFile = appPath($logPath) . '/' . $logName . '.log';
                $fileHandler = new StreamHandler($logFile, Logger::DEBUG);
                $fileHandler->setFormatter($formatter);
                $logger->pushHandler($fileHandler);

                // console handler
                $handler = new StreamHandler('php://stdout', Logger::DEBUG);
                $handler->setFormatter($formatter);
                $logger->pushHandler($handler);



                return $logger;
            }
        );
    }
}

