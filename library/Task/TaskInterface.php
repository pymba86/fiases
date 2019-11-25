<?php

namespace Library\Task;

use Library\State\StateInterface;
use Phalcon\Di\InjectionAwareInterface;

/**
 * Интерфейс для обьекта, который выполняем какую-либо задачу в рамках обновления данных
 * @package Library\Tasks
 */
interface TaskInterface extends InjectionAwareInterface
{

    /**
     * Запускает данную задачу на исполнение
     * @param StateInterface $state
     * @return void
     */
    public function run(StateInterface $state): void;

}