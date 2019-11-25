<?php

namespace Library\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\User\Plugin;
use Library\Http\Response;
use Library\Traits\ResponseTrait;

/**
 * Class NotFoundMiddleware
 *
 * @package Library\Middleware
 *
 * @property Micro    $application
 * @property Response $response
 */
class NotFoundMiddleware extends Plugin implements  MiddlewareInterface {

    use ResponseTrait;

    /**
     * Checks if the resource was found
     */
    public function beforeNotFound()
    {
        $this->halt(
            $this->application,
            404,
            $this->response->getHttpCodeDescription(404)
        );
        return false;
    }


    public function call(Micro $application)
    {
        return true;
    }

}