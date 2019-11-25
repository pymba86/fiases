<?php

namespace Library\Providers;

use Library\Informer\Informer;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;


class InformerProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared('informer', new Informer());
    }
}

