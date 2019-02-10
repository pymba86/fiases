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
            ->command('update:all', 'Зазгрузить все дельты относительно текущей версии')
            ->tap($console)
            ->command('update:load', 'Загрузить определенную дельту версию')
            ->tap($console)
            ->command('update:version', 'Получить актуальную удаленную версию');
    }


    public function mainAction(array $params)
    {

    }

    public function testAction(array $params)
    {
        echo print_r($params);
    }

    public function listAction(array $params)
    {


    }

}