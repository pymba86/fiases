<?php

namespace Library\Providers;

use Phalcon\Config;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

use function Library\Core\appPath;

class ConfigProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared(
            'config',
            function () {
                $data = require appPath('library/Core/config.php');
                return new Config($data);
            }
        );
    }
}

