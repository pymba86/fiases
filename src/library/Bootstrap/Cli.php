<?php

namespace Library\Bootstrap;

use Library\Cli\Console;
use Library\Cli\Task\DataTask;
use Library\Cli\Task\MainTask;
use Library\Cli\Task\TestTask;
use Library\Cli\Task\UpdateTask;
use Library\Cli\Task\VersionTask;
use Phalcon\Config\Adapter\Php as ConfigPhp;
use Phalcon\Di\FactoryDefault\Cli as PhCli;

use function Library\Core\appPath;

class Cli extends AbstractBootstrap
{

    const NAME = 'fias-web';

    const VERSION = '0.0.2';

    const TASKS = [
        'test' => TestTask::class,
        'main' => MainTask::class,
        'update' => UpdateTask::class,
        'data' => DataTask::class,
        'version' => VersionTask::class
    ];

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->application->handle($this->options);
    }

    /**
     * @inheritdoc
     */
    public function setup()
    {
        $this->container = new PhCli();
        $this->providers = new ConfigPhp(appPath("cli/config/providers.php"));
        $this->processArguments();
        parent::setup();
    }

    /**
     * @inheritdoc
     */
    protected function setupApplication()
    {
        $this->application = new Console($this->container, self::NAME, self::VERSION, self::TASKS);
        $this->container->setShared('application', $this->application);
    }

    /**
     * Параметры задач
     */
    private function processArguments()
    {
        $this->options = $_SERVER['argv'];
    }
}