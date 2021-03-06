<?php

namespace Library\Cli\Task;

use Library\Cli\Console;
use Library\Task\Data\DeleteData;
use function Library\Core\appPath;
use Library\Filesystem\DirectoryInterface;
use Library\Informer\InformerResult;
use Library\Mapper\AbstractMapper;
use Library\Pipe\Pipe;
use Library\State\ArrayState;
use Library\Task\Cleanup;
use Library\Task\CreateStructure;
use Library\Task\Data\FilterData;
use Library\Task\Data\InsertData;
use Library\Task\DownloadFull;
use Library\Task\Unpack;
use Library\Task\Version\CurrentVersion;
use Library\Task\Version\UpdateVersion;
use Phalcon\Config\Adapter\Php as ConfigPhp;

/**
 * Основные задания по установке базы
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
            ->command('main:load', 'Загрузить полную актуальную базу', false)
            ->option('-p, --path <path>', 'Путь к файлам xml', null, 'extract')
            ->option('-f, --filter <filter>', 'Путь к отфильтрованным данным xml', null, 'filter')
            ->option('-n, --name <name>', 'Название файла', null, 'archive.rar')
            ->option('-i, --iteration <iteration>', 'Количество итераций чтения в память', null, 10000)
            ->tap($console)
            ->command('main:custom', 'Загрузить базу из директории', false)
            ->option('-p, --path <path>', 'Путь к файлам xml')
            ->option('-f, --filter <filter>', 'Путь к отфильтрованным данным xml', null, 'filter')
            ->option('-i, --iteration <iteration>', 'Количество итераций чтения в память', null, 10000)
            ->option('-b, --branch <branch>', 'Версия базы')
            ->option('-u, --url <url>', 'Url базы');
    }

    public function loadAction(array $params)
    {
        $pipe = new Pipe($this->di);
        $state = new ArrayState();
        $informerResult = new InformerResult();
        /** @var DirectoryInterface $fs */
        $fs = $this->di->get("fs");

        /** @var ConfigPhp $mappers */
        $mappers = new ConfigPhp(appPath("config/mappers.php"));
        /** @var ConfigPhp $filters */
        $filters = new ConfigPhp(appPath("config/filters.php"));

        $dirFilter = $fs->createChildDirectory($params['filter']);
        $dirSource = $fs->createChildDirectory($params['path']);
        $fileArchive = $fs->createChildFile($params['name']);

        $pipe->pipe(new DownloadFull($fileArchive, $informerResult));
        $pipe->pipe(new CurrentVersion($informerResult));
        $pipe->pipe(new Unpack($fileArchive, $dirSource));

        foreach ($mappers as $mapper) {
            $objectMapper = new $mapper();
            
            $pipe->pipe(new FilterData($objectMapper, $dirSource,
                $dirFilter, $params['iteration'], $filters->toArray()));
            
            $pipe->pipe(new CreateStructure($objectMapper));
            $pipe->pipe(new InsertData($objectMapper, $dirFilter));
            $pipe->pipe(new DeleteData($objectMapper, $dirFilter));
        }

        $pipe->pipe(new UpdateVersion($informerResult));
        $pipe->setCleanup(new Cleanup([$dirFilter, $dirSource, $fileArchive]));
        $pipe->run($state);

    }

    public function customAction(array $params)
    {

        $pipe = new Pipe($this->di);
        $state = new ArrayState();

        $informerResult = new InformerResult();
        $informerResult->setVersion(intval($params['branch']));
        $informerResult->setUrl($params['url']);

        /** @var DirectoryInterface $fs */
        $fs = $this->di->get("fs");

        /** @var AbstractMapper[] $mappers */
        $mappers = new ConfigPhp(appPath("config/mappers.php"));
        /** @var ConfigPhp $filters */
        $filters = new ConfigPhp(appPath("config/filters.php"));;

        $dirFilter = $fs->createChildDirectory($params['filter']);
        $dirSource = $fs->createChildDirectory($params['path']);

        $pipe->pipe(new CurrentVersion($informerResult));

        foreach ($mappers as $mapper) {
            $objectMapper = new $mapper();
            
            $pipe->pipe(new FilterData($objectMapper, $dirSource,
                $dirFilter, $params['iteration'], $filters->toArray()));
            
            $pipe->pipe(new CreateStructure($objectMapper));
            $pipe->pipe(new InsertData($objectMapper, $dirFilter));
            $pipe->pipe(new DeleteData($objectMapper, $dirFilter));
        }

        $pipe->pipe(new UpdateVersion($informerResult));
        $pipe->setCleanup(new Cleanup([$dirFilter]));
        $pipe->run($state);
    }
}
