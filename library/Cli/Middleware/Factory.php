<?php

namespace Library\Cli\Middleware;

use Library\Cli\MiddlewareInterface;
use Library\Cli\Console;

/**
 *
 * @package Library\Cli\Middleware
 */
class Factory implements MiddlewareInterface
{

    /**
     * Обработчик на наличие версии и хэлпа
     *
     * @param Console $console
     * @return bool
     */
    public function call(Console $console)
    {
        $rawArgv = $console->argv($raw = true);
        $argv = $console->argv($raw = false);
        $app     = $console->app();
        $command = $app->commandFor($argv);

        if ($this->isVersion($rawArgv)) {
            $command->showVersion();
            return false;
        }

        if ($this->isGlobalHelp($rawArgv)) {
            $app->showHelp();
            return false;
        }

        if ($this->isHelp($rawArgv)) {
            $command->showHelp();
            return false;
        }

        return $console->app()->handle($argv);
    }

    protected function isGlobalHelp(array $argv): bool
    {
        $isGlobal = ($argv[1][0] ?? '-') === '-' && ($argv[2][0] ?? '-') === '-';

        return $isGlobal && $this->isHelp($argv);
    }

    protected function isHelp(array $argv): bool
    {
        return \in_array('--help', $argv) || \in_array('-h', $argv);
    }

    protected function isVersion(array $argv): bool
    {
        return \in_array('--version', $argv) || \in_array('-V', $argv);
    }
}