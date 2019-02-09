<?php

namespace Library\Cli;

use Library\Cli\Middleware\Factory;
use Phalcon\Cop\Parser;
use Library\Cli\Task\AbstractTask;
use Library\Cli\Task\ScheduleTask;
use Phalcon\Cli\Console as PhConsole;
use Ahc\Cli\Input\Command;
use Ahc\Cli\Application;
use Phalcon\DiInterface;

/**
 * Class Console
 * @package Library\Cli
 */
class Console extends PhConsole
{
    /** @var array Пути к заданиями */
    protected $namespaces = [];

    /** @var array Предустановленные задания */
    protected $factoryTasks = [
        'schedule' => ScheduleTask::class
    ];

    /**
     * @var array Предустановленные обработчики
     */
    protected $middlewares = [
        //  Factory::class => 'before'
    ];

    /** @var array Список заданий, которые имеют планировщик */
    protected $scheduled = [];

    /** @var array Аргументы, которые будут отправлены в обработчик */
    protected $rawArgv = [];

    /** @var array Нормализованные аргументы */
    protected $argv = [];

    /** @var string Последняя команда */
    protected $lastCommand;

    /** @var Application */
    protected $application;

    /** @var array Задания */
    protected $tasks = [];

    /** @var bool Последний обработанный статус */
    protected $lastStatus = true;

    /**
     * @param DiInterface $di
     * @param string $name
     * @param string $version
     * @param array $tasks
     */
    public function __construct(DiInterface $di, string $name, string $version, array $tasks = [])
    {
        parent::__construct($di);

        $this->tasks = $tasks;

        $this->application = new Application($name, $version, function (int $onExit = 0) {

            if ($onExit !== 0) {
                $this->lastStatus = false;
            }

            return $onExit === 0;
        });

        $this->initTasks();

        /**
         * Заполняем описания команд
         * @var  $name string
         * @var  $taskClass AbstractTask
         */
        foreach ($this->getTaskClasses() as $name => $taskClass) {
            $taskClass::description($this);
        }

    }

    /**
     * Получить встроенное приложение
     * @return Application
     */
    public function app(): Application
    {
        return $this->application;
    }

    /**
     * Получить необработанные или обработанные значения argv.
     * @param bool $raw Если true исходные значения по умолчанию возвращаются, в противном случае обрабатываются значения.
     * @return array
     */
    public function argv(bool $raw = true): array
    {
        if ($raw) {
            return $this->rawArgv;
        }

        return $this->argv;
    }

    /**
     * Обработать консольный запрос
     * @param array|null $argv
     * @return mixed
     */
    public function handle(array $argv = null)
    {
        $this->rawArgv = $argv ?? $_SERVER['argv'];
        $params = $this->getTaskParameters($this->rawArgv);
        return $this->doHandle($params);
    }

    /**
     * Вызвать обработчик родителя
     * @param array $parameters ['task' => ..., 'action' => ..., 'params' => []]
     */
    public function doHandle(array $parameters)
    {
        // Normalize in the form: ['app', 'task:action', 'param1', 'param2', ...]
        $this->argv = array_merge(
            [$argv[0] ?? null, $parameters['task'] . ':' . $parameters['action']],
            $parameters['params']
        );

        if (isset($this->namespaces[$parameters['task']])) {
            $parameters['task'] = $this->namespaces[$parameters['task']];
            $parameters['params'] = $this->application->parse($this->argv)->values(false);

            if ($this->lastStatus) {
                parent::handle($parameters);
            }

        } else {
            $this->application->handle($this->argv);
        }
    }

    /**
     * Получить параметры задания
     * @param array $argv
     * @return array
     */
    protected function getTaskParameters(array $argv)
    {
        $taskAction = [];
        array_shift($argv);

        foreach ($argv as $i => $value) {
            if ($value[0] === '-' || isset($taskAction[1])) {
                break;
            }

            $taskAction = array_merge($taskAction, explode(':', $value, 2));
            unset($argv[$i]);
        }

        // $taskAction += [null, null];

        return [
            'task' => $taskAction[0],
            'action' => $taskAction[1],
            // For BC, still send params to handle()
            'params' => array_values($argv),
        ];
    }

    /**
     * Получить все запланированные задачи
     * @return array
     */
    public function scheduled(): array
    {
        return $this->scheduled;
    }

    /**
     * Зарегистрируйте новую команду, которая будет управляться / планироваться консолью.
     *
     * @param string $command Формат 'task:action'
     * @param string $description
     * @param bool $allowUnknown Разрешить неизвестные варианты
     * @return Command Команда cli, для которой вы можете определить аргументы / параметры свободно.
     */
    public function command(string $command, string $description = '', bool $allowUnknown = false): Command
    {
        $this->lastCommand = $command;

        if (\strpos($command, ':main')) {
            $alias = \str_replace(':main', '', $command);
        }

        if (\strpos($command, ':') === false) {
            $alias = $command . ':main';
        }

        return $this->application->command($command, $description, $alias ?? '', $allowUnknown);
    }

    /**
     * Запланируйте выполнение команды в тот момент, когда данное выражение cron оценивает значение true.
     * @param string $cronExpr Например: `@hourly` (обратите внимание на Ahc\Cli\Expression для предопределенных значений)
     * @param string $command Это необязательно (по умолчанию он планирует последнюю команду, добавленную через `command ()`)
     *                        Если дано, имя должно соответствовать имени, которое вы передали `addTask ($name)`
     * @return Console
     */
    public function schedule(string $cronExpr, string $command = ''): self
    {
        $command = $command ?: $this->lastCommand;

        $this->scheduled[$command] = $cronExpr;

        return $this;
    }

    /**
     * Начальные задачи.
     * @return Console
     */
    public function initTasks(): self
    {
        foreach ($this->getTaskClasses() as $name => $class) {
            $this->namespaces[$name] = \preg_replace('#Task$#', '', $class);
        }
        return $this;
    }

    /**
     * Получить классы задач
     * @return array
     */
    protected
    function getTaskClasses(): array
    {
        return array_merge($this->factoryTasks, $this->tasks);
    }

    public function middleware(string $class, string $event): self
    {
        $this->middlewares[] = [$class, $event];
        return $this;
    }

    /**
     * Bulk setter/getter.
     *
     * @param array $middlewares Class names.
     *
     * @return array|self
     */
    public function middlewares(array $middlewares = [])
    {
        if (func_num_args() > 0) {
            $this->middlewares = array_unique(array_merge($this->middlewares, $middlewares));

            return $this;
        }

        return $this->middlewares;
    }

    /**
     * Запускаеться список обработчиков перед запуском задачи
     * @return bool
     */
    public function beforeExecuteRoute(): bool
    {
        return $this->relay('before');
    }

    /**
     * Запускаеться список обработчиков после запуска задачи
     * @return bool
     */
    public function afterExecuteRoute(): bool
    {
        return $this->relay('after');
    }

    /**
     * Запуск обработчиков в зависимости от события(before, after)
     * @param string $event
     * @return bool
     */
    protected function relay(string $event): bool
    {
        foreach ($this->middlewares as $middleware => $eventMiddleware) {
            if ($eventMiddleware == $event) {
                /** @var MiddlewareInterface $mid */
                $mid = new $middleware();
                $status = $mid->call($this);

                if (!$status || !$this->lastStatus) {
                    return false;
                }
            }
        }
        return true;
    }
}
