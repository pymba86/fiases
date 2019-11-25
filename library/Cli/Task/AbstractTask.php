<?php

namespace Library\Cli\Task;

use Library\Cli\Console;
use Phalcon\Cli\Task;

abstract class AbstractTask extends Task {

    /**
     * Получить описание задания
     * @param Console $console
     * @return void
     */
    abstract static function description(Console $console): void;

}