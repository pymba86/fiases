<?php

namespace Library\Cli\Task;

use Library\Cli\Console;
use Ahc\Cli\Input\Command;
use function Library\Core\appPath;
use Library\Filesystem\DirectoryInterface;
use Library\Mapper\AbstractMapper;
use Library\Pipe\Pipe;
use Library\State\ArrayState;
use Library\Task\CreateStructure;
use Library\Task\Data\InsertData;
use Phalcon\Config\Adapter\Php as ConfigPhp;

/**
 * @property Command command
 */
class UpdateTask extends AbstractTask
{
    /**
     * @inheritdoc
     */
    static function description(Console $console): void
    {
        $console
            ->command('update:list', 'Update all data in the table')
            ->option('-a, --age [age]', 'Age', 'intval', 0)
            ->tap($console)
            ->command('update:main', 'Update all data in the table')
            ->option('-a, --age [age]', 'Age', 'intval', 0)
            ->tap($console)
            ->command('update:last', 'Update database')
            ->argument('[force]', 'Drop database')
            ->option('-n, --name <name>', 'Name')
            ->option('-a, --age [age]', 'Age', 'intval', 0)
            ->option('-h, --hobbies [...]', 'Hobbies');
    }


    public function mainAction(array $params)
    {
        $pipe = new Pipe($this->di);
        $state = new ArrayState();

        /** @var DirectoryInterface $fs */
        $fs = $this->di->get("fs");

        /** @var AbstractMapper[] $mappers */
        $mappers = new ConfigPhp(appPath("config/mappers.php"));
        /** @var array $filters */
        $filters = new ConfigPhp(appPath("config/filters.php"));

        $dirFilter = $fs->createChildDirectory("filter");

        if ($dirFilter->isExists()) {
            foreach ($mappers as $mapper) {
                // $pipe->pipe(new FilterData(new $mapper(), $dir, 10000, $filters));
               // $pipe->pipe(new CreateStructure(new $mapper()));
                  $pipe->pipe(new InsertData(new $mapper, $dirFilter));
            }

             $pipe->run($state);
        } else {
            echo 'Папка не найдена';
        }
    }

    public function testAction(array $params)
    {
        echo print_r($params);
    }

    public function listAction(array $params)
    {


    }

}