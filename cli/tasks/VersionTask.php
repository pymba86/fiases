<?php

namespace Library\Cli\Task;

use Ahc\Cli\IO\Interactor;
use Exception;
use Library\Cli\Console;
use Library\Search\ConnectionInterface;

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
            ->command('version:info', 'Получить установленную версию')
            ->tap($console);
    }

    /**
     * Получить установленную версию
     * @param array $params
     */
    public function infoAction(array $params)
    {
        $io = new Interactor();

        /** @var ConnectionInterface $search */
        $search = $this->di->getShared('search');

        try {

            $versions = $search->find([
                'index' => 'versions',
                'type' => 'version',
                'limit' => 1,
                'sort' => ['version' => 'desc'],
                'fields' => ['version', 'data'],
            ]);

            $currentVersion = $versions[0];

            if (isset($currentVersion)) {
                $io->boldGreen('Текущая версия: ' . $currentVersion['version'], true);
            } else {
                $io->boldGreen('Нет установленных версий. Запустите полную установку', true);
            }

        } catch (Exception $exception) {
            $io->boldRed('Текущая версия: не определена', true);
            $io->error($exception->getMessage());
        }
    }
}