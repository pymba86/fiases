<?php

ini_set('display_errors', 'stderr');

define('APP_PATH', __DIR__);
require_once 'vendor/autoload.php';
$relay = new Spiral\Goridge\StreamRelay(STDIN, STDOUT);
$psr7 = new Spiral\RoadRunner\PSR7Client(new Spiral\RoadRunner\Worker($relay));

$dumper = new Spiral\Debug\Dumper();
$dumper->setRenderer(Spiral\Debug\Dumper::ERROR_LOG, new Spiral\Debug\Renderer\ConsoleRenderer());

/**
 * Read auto-loader
 */
require_once __DIR__ . '/library/Core/autoload.php';


/**
 * Read services
 */
$bootstrap = new \Library\Bootstrap\Api();
$bootstrap->setup();

while ($req = $psr7->acceptRequest()) {

    try {
        $uriObject = $req->getUri();
        $getPathClosure = function () {
            return $this->path;
        };
        $uri = $getPathClosure->call($uriObject);

        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['REQUEST_METHOD'] = $req->getMethod();
        $_REQUEST['body'] =  $req->getBody()->getContents();
        
        foreach ($req->getQueryParams() as $key => $value) {
            $_GET[$key] = $value;
            $_POST[$key] = $value;
            $_REQUEST[$key] = $value;
        }

        $bootstrap->getApplication()->handle();

        $appResponse = $bootstrap->getResponse();

        $resp = new \Zend\Diactoros\Response('php://temp',
            $appResponse->getStatusCode(),
            ['Content-Type' => $appResponse->getHeaders()->get('Content-Type')]
        );

        $resp->getBody()->write($appResponse->getContent());

        $psr7->respond($resp);

    } catch (Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}
