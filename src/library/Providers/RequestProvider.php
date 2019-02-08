<?php

namespace Library\Providers;

use Library\Http\Request;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;


class RequestProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared('request', new Request());
    }
}

