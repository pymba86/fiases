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
            $this->response::NOT_FOUND,
            $this->response->getHttpCodeDescription($this->response::NOT_FOUND)
        );
        return false;
    }


    public function call(Micro $application)
    {
        return true;
    }

}