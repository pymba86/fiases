<?php

namespace Library\Http;

use Phalcon\Http\Response as PhResponse;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Model\MessageInterface as ModelMessage;
use Phalcon\Validation\Message\Group as ValidationMessage;

use function sha1;
use function date;
use function json_decode;

class Response extends PhResponse
{
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const MOVED_PERMANENTLY = 301;
    const FOUND = 302;
    const TEMPORARY_REDIRECT = 307;
    const PERMANENTLY_REDIRECT = 308;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const BAD_GATEWAY = 502;

    private $codes = [
        200 => 'OK',
        301 => 'Moved Permanently',
        302 => 'Found',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
    ];

    /**
     * Returns the http code description or if not found the code itself
     * @param int $code
     *
     * @return int|string
     */
    public function getHttpCodeDescription(int $code)
    {
        if (true === isset($this->codes[$code])) {
            return sprintf('%d (%s)', $code, $this->codes[$code]);
        }
        return $code;
    }

    /**
     * Send the response back
     *
     * @return ResponseInterface
     */
    public function send(): ResponseInterface
    {
        $content = json_decode($this->getContent(), true);
        $this->setJsonContent($content);
       // return parent::send();
    }

    /**
     * Sets the payload code as Error
     *
     * @param array $detail
     *
     * @return Response
     */
    public function setPayloadError($detail = []): Response
    {
        $this->setJsonContent(['errors' => $detail]);
        return $this;
    }

    /**
     * Traverses the errors collection and sets the errors in the payload
     *
     * @param ModelMessage[]|ValidationMessage $errors
     *
     * @return Response
     */
    public function setPayloadErrors($errors): Response
    {
        $data = [];
        foreach ($errors as $error) {
            $data[] = $error->getMessage();
        }
        $this->setJsonContent(['errors' => $data]);
        return $this;
    }

    /**
     * Sets the payload code as Success
     *
     * @param null|string|array $content The content
     *
     * @return Response
     */
    public function setPayloadSuccess($content = []): Response
    {
        $data = (true === is_array($content)) ? $content : ['data' => $content];
        $data = (true === isset($data['data'])) ? $data : ['data' => $data];
        $this->setJsonContent($data);
        return $this;
    }
}