<?php

namespace Library\Providers;

use Library\Xml\Reader;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;


class ReaderProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared('reader', new Reader());
    }
}

