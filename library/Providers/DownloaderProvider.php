<?php

namespace Library\Providers;

use Library\Downloader\Curl;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;


class DownloaderProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared('downloader', new Curl());
    }
}

