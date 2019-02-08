<?php
namespace Library\Providers;

use Library\Cli\Logger;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;


class ConsoleLogProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared('logger', new Logger());
    }
}

