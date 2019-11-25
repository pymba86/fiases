<?php


namespace Library\Traits;

use Library\Http\Response;
use Phalcon\Mvc\Micro;


trait ResponseTrait
{
    /**
     * Остановить выполнение после установки сообщения в ответ
     *
     * @param Micro $api
     * @param string $message
     * @param int $status
     *
     * @return void
     */
    protected function halt(Micro $api, int $status, string $message): void
    {
        /** @var Response $response */
        $response = $api->getService('response');
        $response
            ->setPayloadError($message)
            ->setStatusCode($status);
        
        // $api->stop();
    }
}