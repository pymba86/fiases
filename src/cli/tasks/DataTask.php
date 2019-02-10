<?php

namespace Library\Cli\Task;

use Ahc\Cli\IO\Interactor;
use Library\Cli\Console;
use function Library\Core\appPath;
use Library\Filesystem\DirectoryInterface;
use Library\Mapper\AbstractMapper;
use Library\Pipe\Pipe;
use Library\State\ArrayState;
use Library\Task\Cleanup;
use Library\Task\CreateStructure;
use Library\Task\Data\FilterData;
use Phalcon\Config\Adapter\Php as ConfigPhp;

/**
 * Задания, которые обрабатывают данные
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
            ->command('data:list', 'Список файлов для мапперов', false)
            ->tap($console)
            ->command('data:filter', 'Фильтрация данных', true)
            ->option('-p, --path <path>', 'Путь к файлам xml')
            ->option('-f, --filter <filter>', 'Путь к отфильтрованным данным xml')
            ->tap($console)
            ->command('data:remove', ' Очистка временной директории', false)
            ->option('-p, --path <path>', 'Путь к директории')
            ->tap($console)
            ->command('data:create', 'Создание структуры данных', true)
            ->tap($console);
    }

    public function createAction(array $params)
    {
        $pipe = new Pipe($this->di);
        $state = new ArrayState();

        /** @var AbstractMapper[] $mappers */
        $mappers = new ConfigPhp(appPath("config/mappers.php"));

        foreach ($mappers as $mapper) {
            $pipe->pipe(new CreateStructure(new $mapper()));
        }

        $pipe->run($state);
    }

    public function removeAction(array $params)
    {
        $io = new Interactor();
        $pipe = new Pipe($this->di);
        $state = new ArrayState();
        $path = $params['path'];

        /** @var DirectoryInterface $fs */
        $fs = $this->di->get("fs");
        $dir = $fs->createChildDirectory($path);

        if ($dir->isExists()) {
            $pipe->pipe(new Cleanup([$dir]));
            $pipe->run($state);
        } else {
            $io->boldRed('Директория не найдена', true);
        }
    }

    public function mainAction(array $params)
    {
        $io = new Interactor();
        $pipe = new Pipe($this->di);
        $state = new ArrayState();

        /** @var DirectoryInterface $fs */
        $fs = $this->di->get("fs");

        /** @var AbstractMapper[] $mappers */
        $mappers = new ConfigPhp(appPath("config/mappers.php"));
        /** @var array $filters */
        $filters = new ConfigPhp(appPath("config/filters.php"));

        $dirFilter = $fs->createChildDirectory($params['filter']);
        $dirSource = $fs->createChildDirectory($params['path']);

        if ($dirSource->isExists()) {
            foreach ($mappers as $mapper) {
                $pipe->pipe(new FilterData(new $mapper(), $dirSource, $dirFilter, 10000, $filters));
            }
            $pipe->run($state);
        } else {
            $io->boldRed('Директория не найдена', true);
        }
    }
}