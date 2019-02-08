<?php

namespace Library\Bootstrap;

use Phalcon\Cli\Console;
use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Response;
use Phalcon\Mvc\Micro;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Di\FactoryDefault\Cli as PhCli;


abstract class AbstractBootstrap
{

    /**
     * Экземпляр приложения
     * @var Micro | Console
     */
    protected $application;

    /**
     * Контейнер DI
     * @var FactoryDefault | PhCli
     */
    protected $container;

    /**
     * Параметры приложения
     * @var array
     */
    protected $options = [];

    /**
     * Поставщики приложения
     * @var ServiceProviderInterface[]
     */
    protected $providers = [];


    /**
     * Получить экзэмлпяр приложения
     * @return Console|Micro
     */
    public function getApplication()
    {
        return $this->application;
    }


    /**
     * Запустить приложение
     * @return mixed
     */
    abstract public function run();


    /**
     * Получить контейнер
     * @return FactoryDefault|PhCli
     */
    public function getContainer()
    {
        return $this->container;
    }


    /**
     * Получить параметры
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }


    /**
     * Получить поставщиков
     * @return ServiceProviderInterface[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }


    /**
     * Создать экземпляр
     */
    public function setup()
    {
        $this->container->set('metrics', microtime(true));
        $this->setupApplication();
        $this->registerServices();
    }

    /**
     * Создать новое Micro приложение
     */
    protected function setupApplication()
    {
        $this->application = new Micro($this->container);
        $this->container->setShared('application', $this->application);
    }


    /**
     * Зарегистрировать поставщиков
     */
    private function registerServices()
    {
        /** @var ServiceProviderInterface $provider */
        foreach ($this->providers as $provider) {
            $this->container->register(new $provider());
        }
    }

    /**
     * Получить ответ приложения для текущей сессии
     * @return Response
     */
    public function getResponse()
    {
        return $this->container->getShared('response');
    }


}