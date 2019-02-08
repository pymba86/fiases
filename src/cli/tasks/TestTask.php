<?php

namespace Library\Cli\Task;

use Library\Cli\Console;
use Ahc\Cli\Input\Command;

/**
 * @property Command command
 */
class TestTask extends AbstractTask
{
    /**
     * @inheritdoc
     */
    static function description(Console $console): void
    {
        $console
            ->command('test:list', 'List scheduled tasks (if any)')
            ->tap($console);
    }

    public function mainAction(array $params)
    {
        echo print_r($params);
    }

    public function testAction(array $params)
    {
        echo print_r($params);
    }


}