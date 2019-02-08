<?php

namespace Library\Cli\Task;

use Library\Cli\Console;

/**
 * Управление версией
 * @package Library\Cli\Task
 */
class VersionTask extends AbstractTask
{
    /**
     * @inheritdoc
     */
    static function description(Console $console): void
    {
        $console
            ->command('version:current', 'List scheduled tasks (if any)')
            ->tap($console)
            ->command('version:run', 'Run scheduled tasks that are due')
            ->tap($console);
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