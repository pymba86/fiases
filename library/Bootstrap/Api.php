<?php

namespace Library\Bootstrap;

use function Library\Core\appPath;
use Phalcon\Di\FactoryDefault;
use Phalcon\Config\Adapter\Php as ConfigPhp;

class Api extends AbstractBootstrap
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->application->handle();
    }

    /**
     * @inheritdoc
     */
    public function setup()
    {
        $this->container = new FactoryDefault();
        $this->providers = new ConfigPhp(appPath("api/config/providers.php"));
        parent::setup();
    }


}