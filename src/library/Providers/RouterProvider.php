<?php

namespace Library\Providers;

use Library\Api\Controllers\SearchController;
use Library\Middleware\ResponseMiddleware;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection;

use Library\Middleware\NotFoundMiddleware;

class RouterProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        /** @var Micro $application */
        $application = $container->getShared('application');

        /** @var Manager $eventsManager */
        $eventsManager = $container->getShared('eventsManager');

        $this->attachRoutes($application);
        $this->attachMiddleware($application, $eventsManager);

        $application->setEventsManager($eventsManager);
    }

    /**
     * Прикрепляем маршруты к приложению
     * @param Micro $application
     */
    private function attachRoutes(Micro $application)
    {
        $routes = $this->getRoutes();

        foreach ($routes as $route) {
            $collection = new Collection();
            $collection
                ->setHandler($route[0], true)
                ->setPrefix($route[1])
                ->{$route[2]}($route[3], 'callAction');

            $application->mount($collection);
        }
    }

    /**
     * Получить список маршутов
     * @return array
     */
    private function getRoutes()
    {
        $routes = [
            // Class, Method, Route, Handler
             [SearchController::class, '/', 'post', '/']
        ];

        return $routes;
    }

    /**
     * Прикрепляем обработчики запроса к приложению
     * @param Micro $application
     * @param Manager $eventsManager
     */
    private function attachMiddleware(Micro $application, Manager $eventsManager)
    {
        $middleware = $this->getMiddleware();

        foreach ($middleware as $class => $function) {
            $eventsManager->attach('micro', new $class());
            $application->{$function}(new $class());
        }
    }

    /**
     * Получить обработчики запроса
     * @return array
     */
    private function getMiddleware()
    {
        return [
            NotFoundMiddleware::class => 'before',
            ResponseMiddleware::class => 'after'
        ];
    }
}

