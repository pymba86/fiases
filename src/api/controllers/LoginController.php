<?php

namespace Library\Api\Controllers;


use Phalcon\Filter;
use Phalcon\Mvc\Controller;

use Library\Http\Response;

/**
 * Class LoginController
 *
 * @package Library\Api\Controllers
 *
 * @property Response $response
 */
class LoginController extends Controller
{

    /**
     *
     */
    public function callAction()
    {
        $this
            ->response
            ->setPayloadSuccess(['token' => 'v0.11.0']);

    }
}