<?php

namespace Library\Task;

use Library\Filesystem\CleanableInterface;
use Library\State\StateInterface;

/**
 * Задача, которая удаляет все временные данные, созданные остальными задачами
 * @package Library\Task
 */
class Cleanup extends AbstractTask
{

    /**
     * Спискок временных обьектов в файловой системе, которые нужно удалить
     * @var CleanableInterface[]
     */
    protected $temps;

    /**
     * Удаляет все переданные обьекты из файловой системы
     * @param CleanableInterface[] $temps
     */
    public function __construct(array $temps)
    {
        $this->temps = $temps;
    }

    /**
     * @inheritdoc
     */
    public function run(StateInterface $state): void
    {
        $this->info("Началась очистка временных обьектов файловой системы");

        foreach ($this->temps as $temp) {
            if ($temp->isExists()) {
                $temp->delete();
            }
        }

        $this->info("Очистка успешна завершена");
    }
}