<?php

namespace Library\Api\Controllers;

use Exception;
use Library\Search\ConnectionInterface;
use Phalcon\Mvc\Controller;
use Library\Http\Response;

/**
 * Class LoginController
 *
 * @package Library\Api\Controllers
 *
 * @property Response $response
 */
class SearchController extends Controller
{
    /**
     * Поиск записей
     */
    public function callAction()
    {
        $request = $this->request->getJsonRawBody(true);
        /** @var ConnectionInterface $search */
        $search = $this->di->getShared('search');

        if (is_array($request)) {
            try {
                $response = $search->find($request);
                $this->response->setPayloadSuccess($response);

            } catch (Exception $exception) {
                $error = json_decode($exception->getMessage(), true);
                $this->response->setPayloadError($error);
            }
        } else {
            $this->response->setPayloadError(['error' => 'request is empty or is not valid']);
        }
    }
}
