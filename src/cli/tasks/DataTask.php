<?php

namespace Library\Cli\Task;

use Library\Cli\Console;

/**
 * Задание, которое обрабатывается в случае если нет переданных комманд
 * @package Library\Cli\Task
 */
class DataTask extends AbstractTask
{
    /**
     * @inheritdoc
     */
    static function description(Console $console): void
    {
        $console
            ->command('data:list', 'List scheduled tasks (if any)', false)
            ->tap($console)
            ->command('data:run', 'Run scheduled tasks that are due', true);
    }

    /**
     * Показываем справку, в случае если нет аргументов
     * @param array $params
     */
    public function mainAction(array $params)
    {
        echo print_r($params);
    }
}