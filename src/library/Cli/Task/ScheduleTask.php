<?php

namespace Library\Cli\Task;

use Library\Cli\Console;
use Ahc\Cli\IO\Interactor;
use Ahc\Cron\Expression;

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

    /**
     * Получить список всех заданий планировщика
     */
    public function listAction(): void
    {
        $io = new Interactor();

        if ([] !== $tasks = $this->di->getShared('application')->scheduled()) {
            $io->boldGreen('Schedules:', true);

            $maxLen = max(array_map('strlen', array_keys($tasks)));

            foreach ($tasks as $taskId => $schedule) {
                $io->bold('  ' . str_pad($taskId, $maxLen + 2))->comment($schedule, true);
            }
        } else {
            $io->boldGreen('No scheduled tasks', true);
        }
    }

    /**
     * Запустить задания с учетом выражения крона
     */
    public function runAction(): void
    {
        $io = new Interactor();

        if ([] !== $tasks = $this->dueTasks()) {
            $params = [];
            foreach ($tasks as list($task, $action)) {
                $io->yellow($task . ':' . $action, true);
                $this->di->getShared('application')->doHandle(compact('task', 'action', 'params'));
            }
        } else {
            $io->boldGreen('No due tasks for now', true);
        }
    }

    /**
     * Получить задания которые нужно выполнить в данный момент
     * @return array
     */
    protected function dueTasks(): array
    {
        if ([] === $tasks = $this->di->getShared('application')->scheduled()) {
            return [];
        } else {
            $dues = [];
            $now = time();
            $cron = new Expression;

            foreach ($tasks as $taskId => $schedule) {
                if ($cron->isCronDue($schedule, $now)) {
                    $dues[] = explode(':', $taskId) + ['', 'main'];
                }
            }

            return $dues;
        }
    }
}