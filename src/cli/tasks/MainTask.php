<?php

namespace Library\Cli\Task;

use Library\Cli\Console;

/**
 * Задание, которое обрабатывается в случае если нет переданных комманд
 * @package Library\Cli\Task
 */
class MainTask extends AbstractTask
{
    /**
     * @inheritdoc
     */
    static function description(Console $console): void
    {
        $console
            ->command('main:list', 'List scheduled tasks (if any)', false)
            ->tap($console)
            ->command('main:run', 'Run scheduled tasks that are due', true);
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