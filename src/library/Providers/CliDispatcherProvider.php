<?php

namespace Library\Providers;

use Phalcon\Cli\Console;
use Phalcon\Cli\Dispatcher;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Events\Manager as EventsManager;

class CliDispatcherProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        /** @var Console $application */
        $application = $container->getShared('application');

        $container->setShared(
            'dispatcher',
            function () use ($application) {
                $dispatcher = new Dispatcher();
                $eventsManager = new EventsManager();
                $dispatcher->setEventsManager($eventsManager);
                $eventsManager->attach('dispatch', $application);
                return $dispatcher;
            }
        );

    }
}

