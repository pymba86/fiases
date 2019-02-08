<?php

namespace Library\Pipe;

use Library\State\StateInterface;
use Library\Task\TaskInterface;

/**
 * Интерфейс для обьекта, который хранит в себе очередь задач и позволяет запустить их на исполнение
 * @package Library\Pipe
 */
interface PipeInterface
{
    /**
     * Добавляет задачу в очередь
     * @param TaskInterface $task
     * @return self
     */
    public function pipe(TaskInterface $task): PipeInterface;

    /**
     * Задает задачу, которая будет запускаться после каждого заверщения очереди
     * @param TaskInterface $task
     * @return self
     */
    public function setCleanup(TaskInterface $task): PipeInterface;

    /**
     * Запускает все задачи в очереди на исполнение
     * @param StateInterface $state
     * @return void
     */
    public function run(StateInterface $state): void;

    /**
     * Обработка завершения задачи.
     * @param StateInterface $state
     */
    public function cleanup(StateInterface $state): void;

}