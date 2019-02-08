<?php

namespace Library\Task\Data;

use Library\Filesystem\FileInterface;
use Library\Mapper\AbstractMapper;
use Library\Search\ConnectionInterface;
use Library\Xml\ReaderInterface;
use RuntimeException;
use Library\Filesystem\DirectoryInterface;
use Library\State\StateInterface;
use Library\Task\AbstractTask;

/**
 * Абстрактный класс для задачи, связанных с записью данных из xml в БД
 * @package Library\Task\Data
 */
abstract class AbstractDataTask extends AbstractTask
{

    /** @var AbstractMapper */
    protected $mapper;

    /**
     * Директория где будет храниться данные
     * @var DirectoryInterface
     */
    protected $dir;

    /**
     * Задача связанная с записью данных с помощью маппинга
     * @param AbstractMapper $mapper
     * @param DirectoryInterface $dir
     */
    public function __construct(AbstractMapper $mapper, DirectoryInterface $dir)
    {
        $this->mapper = $mapper;
        $this->dir = $dir;
    }

    /**
     * Возвращаем описание задачи для логов
     * @return string
     */
    abstract protected function getTaskDescription(): string;


    /**
     * Ищет файл, который следует импортировать, в указанной папке
     * @param DirectoryInterface $dir
     * @return FileInterface | null
     */
    abstract protected function searchFileInDir(DirectoryInterface $dir);

    /**
     * Обрабатывает обьект, который удалось прочитать из файла
     * @param array $item
     * @return void
     */
    abstract protected function processItem(array $item);

    /**
     * @inheritdoc
     */
    public function run(StateInterface $state): void
    {

        $this->info("Поиск XML файла" .
            " {$this->mapper->getInsertFileMask()} / {$this->mapper->getDeleteFileMask()}" .
            " в {$this->dir->getPath()} директории");

        $file = $this->searchFileInDir($this->dir);

        // Обрабатываем файл в случае если он найден

        if ($file) {
            $this->processFile($file);
        } else {
            $this->info("XML файл не найден, текущее задание пропушено");
        }
    }

    /**
     * Обрабатывает xml файл
     * @param FileInterface $file
     * @return void
     * @throws RuntimeException
     */
    protected function processFile(FileInterface $file): void
    {
        $this->info("Началось чтение {$file->getPath()} файла");

        /** @var ReaderInterface $reader */
        $reader = $this->di->get("reader");

        /** @var ConnectionInterface $search */
        $search = $this->di->get("search");

        $reader->setMapper($this->mapper);

        if ($reader->openFile($file->getPath())) {

            $this->beforeRead($file);

            $processedItems = 0;

            foreach ($reader as $item) {
                $this->processItem($item);
                ++$processedItems;
            }

            $search->complete();
            $this->afterRead($file);

            $this->info('Чтение и обработка завершена, '
                . number_format($processedItems, 0, '.', ' ')
                . ' обработано элементов');

            $reader->closeFile();

        } else {
            $this->errorRead($file);
            throw new RuntimeException(
                "Can't open xml file " . $file->getPath() . ' for reading'
            );
        }

    }

    /**
     * Событие, которое запускается перед чтением файла
     * @param FileInterface $file
     * @return void
     */
    protected function beforeRead(FileInterface $file): void
    {
        // Пустое место :-)
    }

    /**
     * Событие, которое запускается в случае чтением файла
     * @param FileInterface $file
     * @return void
     */
    protected function errorRead(FileInterface $file): void
    {
        // Пустое место :-)
    }

    /**
     * Событие, которое запускается после чтением файла
     * @param FileInterface $file
     * @return void
     */
    protected function afterRead(FileInterface $file): void
    {
        // Пустое место :-)
    }
}