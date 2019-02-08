<?php

namespace Library\Cli\Task;

use Library\Cli\Console;

class ScheduleTask extends AbstractTask
{
    /**
     * @inheritdoc
     */
    static function description(Console $console): void
    {
        $console
            ->command('schedule:list', 'List scheduled tasks (if any)', false)
            ->tap($console)
            ->command('schedule:run', 'Run scheduled tasks that are due', true);
    }
}