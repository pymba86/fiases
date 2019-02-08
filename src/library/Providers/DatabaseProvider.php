<?php

namespace Library\Providers;

use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

use function Library\Core\envValue;

class DatabaseProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared(
            'db',
            function () {
                $options = [
                    'hosts' => envValue('DATA_API_MYSQL_HOST', 'localhost'),
                    'username' => envValue('DATA_API_MYSQL_USER', 'root'),
                    'password' => envValue('DATA_API_MYSQL_PASSWORD', ''),
                    'dbname' => envValue('DATA_API_MYSQL_NAME', 'db'),
                ];

                $connection = new Mysql($options);
                // Set everything to UTF8
                $connection->execute('SET NAMES utf8mb4', []);

                return $connection;
            }
        );
    }
}

