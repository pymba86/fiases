<?php

namespace Library\Providers;

use Library\Http\Response;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;


class ResponseProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared(
            'response',
            function () {
                $response = new Response();
                /**
                 * Assume success. We will work with the edge cases in the code
                 */
                $response
                    ->setStatusCode(200)
                    ->setContentType('application/json', 'UTF-8');
                return $response;
            });
    }
}

