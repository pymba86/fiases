<?php

namespace Library\Pipe;

use Exception;
use Library\Task\TaskInterface;
use Library\State\StateInterface;
use Phalcon\DiInterface;
use RuntimeException;


/**
 * Обьект, который хранит в себе очередь задач и позволяет запустить их на исполнение
 * @package Library\Pipe
 */
class Pipe implements PipeInterface
{

    /** @var DiInterface */
    protected $di;

    /** @var TaskInterface | null */
    protected $cleanup;

    /** @var float */
    protected $pipeStart;

    /** @var TaskInterface[] */
    protected $tasks = [];

    /**
     * Задать DI
     * @param DiInterface $di
     */
    public function __construct(DiInterface $di)
    {
        $this->di = $di;
    }

    /**
     * @inheritdoc
     */
    public function pipe(TaskInterface $task): PipeInterface
    {
        $this->tasks[] = $task;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCleanup(TaskInterface $task): PipeInterface
    {
        $this->cleanup = $task;
        $this->cleanup->setDI($this->di);
        return $this;
    }

    /**
     * @inheritdoc
     * @throws RuntimeException
     */
    public function run(StateInterface $state): void
    {
        foreach ($this->tasks as $task) {
            if (!$state->isCompleted()) {
                try {
                    $task->setDI($this->di);
                    $task->run($state);
                } catch (Exception $e) {
                    $this->cleanup($state);
                    throw new RuntimeException($e->getMessage(), (int)$e->getCode(), $e);
                }
            }
        }

        $this->cleanup($state);
    }

    /**
     * @inheritdoc
     */
    public function cleanup(StateInterface $state): void
    {
        if ($this->cleanup) {
            $this->cleanup->run($state);
        }
    }

}