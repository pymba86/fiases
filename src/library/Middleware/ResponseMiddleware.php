<?php

namespace Library\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Library\Http\Response;
use Library\Traits\ResponseTrait;

/**
 * Class ResponseMiddleware
 *
 * @package Library\Middleware
 *
 * @property Micro    $application
 * @property Response $response
 */
class ResponseMiddleware  implements  MiddlewareInterface {

    use ResponseTrait;

    public function call(Micro $application)
    {
        /** @var Response $response */
        $response = $application->getService('response');
        $response->send();
        return true;
    }

}