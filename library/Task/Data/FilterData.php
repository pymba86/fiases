<?php

namespace Library\Task\Data;

use Library\Filesystem\DirectoryInterface;
use Library\Filesystem\FileInterface;
use Library\Mapper\AbstractMapper;
use Library\State\StateInterface;
use Library\Task\AbstractTask;
use Library\Xml\ReaderInterface;
use RuntimeException;
use XMLWriter;

/**
 * Задача для фильтрации данных для указанного маппера с последующей записью данных в xml
 *
 * @package Library\Task\Data
 */
class FilterData extends AbstractTask
{
    /** @var AbstractMapper */
    protected $mapper;

    /**
     * Директория где  храниться  данные
     * @var DirectoryInterface
     */
    protected $dir;

    /**
     * Директория где будут храниться отфильтрованные  данные
     * @var DirectoryInterface
     */
    protected $filterDir;

    /**
     * Список полей, которые должны быть в списках при определенных условиях
     * В случае если в этом обьекте не будет данного поля - обьект является валидным
     * @var array
     */
    protected $fields;

    /**
     * Количество итераций после которых сбрасывается данные из памяти в файл
     * Используется для ограниечения используемой памяти при обработке файла/
     * Меньше запросов к диску
     * @var int
     */
    protected $iterations;

    /**
     * Отфильтровать данные по мапперу с записью в xml в данную директорию
     * @param AbstractMapper $mapper
     * @param DirectoryInterface $dir
     * @param DirectoryInterface $filterDir
     * @param int $iterations
     * @param array $fields
     */
    public function __construct(AbstractMapper $mapper,
                                DirectoryInterface $dir,
                                DirectoryInterface $filterDir,
                                int $iterations = 1, array $fields = [])
    {
        $this->mapper = $mapper;
        $this->dir = $dir;
        $this->iterations = $iterations;
        $this->fields = $fields;
        $this->filterDir = $filterDir;
    }

    /**
     * @inheritdoc
     * @throws \ReflectionException
     */
    public function run(StateInterface $state): void
    {
        if (!$this->filterDir->isExists()) {
            $this->filterDir->create();
        }

        $this->processFileToMask($this->dir, $this->mapper->getInsertFileMask());
        $this->processFileToMask($this->dir, $this->mapper->getDeleteFileMask());
    }

    /**
     * Запустить обработку файла по маске файла
     * @param DirectoryInterface $dir
     * @param string $mask
     * @throws \ReflectionException
     */
    protected function processFileToMask(DirectoryInterface $dir, string $mask): void
    {
        $file = $this->searchFileInDir($dir, $mask);

        if ($file) {
            $this->processFile($file);
        } else {
            $this->info("XML файл {$mask} не найден, текущее задание пропушено");
        }
    }

    /**
     * Обрабатывает xml файл
     * @param FileInterface $file
     * @return void
     * @throws RuntimeException
     * @throws \ReflectionException
     */
    protected function processFile(FileInterface $file): void
    {
        $this->info("Началось чтение {$file->getPath()} файла");

        /** @var ReaderInterface $reader */
        $reader = $this->di->get("reader");

        $reader->setMapper($this->mapper);

        if ($reader->openFile($file->getPath())) {

            $xmlFile = $this->createFile($file);

            $xmlWriter = new XMLWriter();
            $xmlWriter->openMemory();
            $xmlWriter->startDocument('1.0', 'utf-8');
            $xmlWriter->startElement($this->mapper->getXmlPathRoot());

            $processedItems = 0;
            $validationItems = 0;

            foreach ($reader as $item) {

                if (($validationItems > 0) && ($validationItems % $this->iterations == 0)) {
                    // Сбрасываем данные на диск
                    $this->flush($xmlFile, $xmlWriter);
                }

                if ($this->validationItem($item)) {
                    $this->processItem($item, $xmlWriter);
                    ++$validationItems;
                }

                ++$processedItems;
            }

            $xmlWriter->endElement();
            $this->flush($xmlFile, $xmlWriter);

            $this->info('Чтение и обработка завершена, '
                . number_format($validationItems, 0, '.', ' ')
                . '/'
                . number_format($processedItems, 0, '.', ' ')
                . ' обработано элементов');

            $reader->closeFile();

        } else {
            throw new RuntimeException(
                "Can't open xml file " . $file->getPath() . ' for writing'
            );
        }

    }

    /**
     * Ищет файл, который следует импортировать, в указанной папке
     * @param DirectoryInterface $dir
     * @param string $maskFile
     * @return FileInterface | null
     */
    protected function searchFileInDir(DirectoryInterface $dir, string $maskFile)
    {
        $this->info("Поиск XML файла {$maskFile} в {$dir->getPath()} директории");

        $files = $dir->findFilesByPattern($maskFile);
        $file = reset($files);

        return $file;
    }

    /**
     * Обрабатывает обьект, который удалось прочитать из файла
     * @param array $item
     * @param XMLWriter $writer
     * @return void
     * @throws \ReflectionException
     */
    protected function processItem(array $item, XMLWriter $writer): void
    {
        $item = $this->mapper->convertToStrings($item);
        $writer->startElement($this->mapper->getXmlPathElement());

        foreach ($item as $name => $value) {
            $writer->writeAttribute($name, $value);
        }

        $writer->endElement();
    }

    /**
     * Проверяем обьект на валидность
     * @param array $item
     * @return bool
     */
    protected function validationItem(array $item): bool
    {
        foreach ($this->fields as $nameField => $valueField) {
            if (array_key_exists($nameField, $item)) {
                if ($item[$nameField] != $valueField) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Сбросить данные из памяти в файл
     * @param FileInterface $file
     * @param XMLWriter $writer
     */
    protected function flush(FileInterface $file, XMLWriter $writer)
    {
        file_put_contents($file->getPath(), $writer->flush(true), FILE_APPEND);
    }

    /**
     * Создаем файл xml для записи и очищаем в случае если он есть
     *
     * @param FileInterface $file
     * @return FileInterface
     */
    protected function createFile(FileInterface $file): FileInterface
    {
        $file = $this->filterDir->createChildFile("{$file->getFilename()}.XML");
        file_put_contents($file->getPath(), null);
        return $file;
    }
}