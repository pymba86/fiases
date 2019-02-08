<?php

namespace Library\Task;

use InvalidArgumentException;
use Library\Filesystem\DirectoryInterface;
use Library\Filesystem\FileInterface;
use Library\State\StateInterface;
use Library\Unpacker\UnpackerInterface;

/**
 * Задача для распаковки архива с ФИАС
 * @package Library\Task
 */
class Unpack extends AbstractTask
{
    /**
     * Файл который будет распаковываться
     * @var FileInterface
     */
    protected $file;

    /**
     * Директория в которую будет распакован
     * @var DirectoryInterface
     */
    protected $dir;

    /**
     * @param FileInterface $file
     * @param DirectoryInterface $dir
     */
    public function __construct(FileInterface $file, DirectoryInterface $dir)
    {
        $this->file = $file;
        $this->dir = $dir;
    }

    /**
     * @inheritdoc
     */
    public function run(StateInterface $state): void
    {
        // Создаем поддерикторию в рабочей папке

        if (!$this->dir->isExists()) {
            $this->info("Создаем новую директорию {$this->dir->getPath()} для извлечения данных");
            $this->dir->create();
        } else {
            $this->info("Определена новая директория {$this->dir->getPath()} для извлечения данных");
        }

        // Распаковываем архив

        /** @var UnpackerInterface $unpacker */
        $unpacker = $this->di->get("unpacker");

        $this->info("Распаковка архива " . $this->file->getPath()
            . ' в папку ' . $this->dir->getPath());

        $unpacker->unpack($this->file, $this->dir);

        $this->info('Распаковка завершена');
    }
}