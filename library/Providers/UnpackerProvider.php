<?php

namespace Library\Providers;

use Library\Unpacker\Rar;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;


class UnpackerProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared('unpacker', new Rar());
    }
}

