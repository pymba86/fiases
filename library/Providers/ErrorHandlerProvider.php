<?php

namespace Library\Providers;

use Monolog\Logger;
use Library\Handlers\ErrorHandler;
use Phalcon\Config;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

class ErrorHandlerProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        /** @var Logger $logger */
        $logger = $container->getShared('logger');

        /** @var Config $config */
        $config = $container->getShared('config');

        $handler = new ErrorHandler($logger, $config);
        set_error_handler([$handler, 'handle']);
        register_shutdown_function([$handler, 'shutdown']);
    }
}

