<?php

namespace Library\Filesystem;

use CallbackFilterIterator;
use DirectoryIterator;
use InvalidArgumentException;
use Iterator;
use RuntimeException;

/**
 * Обьект, который инкапсулирует обращение к папке в локальной файловой системе
 * @package Library\Services\Filesystem
 */
class Directory implements DirectoryInterface
{

    /**
     * Абсолютный путь к папке
     * @var string
     */
    protected $path;

    /**
     * Информация о каталоге.
     *
     * @var string[]
     */
    protected $info = [];

    /**
     * Обьект для создания новых файлов
     * @var FileBuilderInterface
     */
    protected $fileBuilder;

    /**
     * Обьект для создание новых директорий
     * @var DirectoryBuilderInterface
     */
    protected $dirBuilder;

    /**
     * Внутрений итератор для обхода вложеннх файлов и папок
     * @var DirectoryIterator;
     */
    protected $iterator;

    /**
     * Задает абсолютный путь к папке, а так же классы для создания вложенных папок и файлов
     * @param string $absolutePath
     * @param DirectoryBuilderInterface $dirBuilder
     * @param FileBuilderInterface $fileBuilder
     */
    public function __construct(string $absolutePath, DirectoryBuilderInterface $dirBuilder, FileBuilderInterface $fileBuilder)
    {

        if (trim($absolutePath, ' \t\n\r\0\x0B\\/') === '') {
            throw new InvalidArgumentException(
                "absolutePath parameter can't be empty"
            );
        }

        if (!preg_match('/^\/[a-z_]+.*[^\/]+$/', $absolutePath)) {
            throw new InvalidArgumentException(
                'absolutePath must starts from root, and consist of digits and letters'
            );
        }

        $this->path = $absolutePath;
        $this->fileBuilder = $fileBuilder;
        $this->dirBuilder = $dirBuilder;
    }

    /**
     * Возвращает внутренний обьект итератора для перебора содержимого данной папки
     * @return DirectoryIterator
     */
    protected function getIterator(): Iterator
    {
        if ($this->iterator === null) {
            $dirIterator = new DirectoryIterator($this->getPath());
            $filter = function (string $current, string $key, DirectoryIterator $iterator): bool {
                return !$iterator->isDot();
            };
            $this->iterator = new CallbackFilterIterator($dirIterator, $filter);
        }

        return $this->iterator;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        $item = $this->getIterator()->current();

        if ($item->isDir()) {
            return $this->createChildDirectory($item->getFilename());
        } else {
            return $this->createChildFile($item->getFilename());
        }
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->getIterator()->next();
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->getIterator()->key();
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        $this->getIterator()->valid();
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->getIterator()->rewind();
    }


    /**
     * @inheritdoc
     */
    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * @inheritdoc
     */
    public function isExists(): bool
    {
        return is_dir($this->path);
    }

    /**
     * @inheritdoc
     */
    public function delete(): DirectoryInterface
    {
        foreach ($this as $child) {
            $child->delete();
        }
        if (!@rmdir($this->getPath())) {
            throw new RuntimeException(
                "Can't delete directory: " . $this->getPath()
            );
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function create(): DirectoryInterface
    {
        if (!@mkdir($this->getPath(), 0777, true)) {
            throw new RuntimeException(
                "Can't create directory " . $this->getPath()
            );
        }
        return $this;
    }


    /**
     * @inheritdoc
     */
    public function createChildDirectory(string $name): DirectoryInterface
    {
        if (!preg_match('/^[a-z0-9_\-]*$/i', $name)) {
            throw new InvalidArgumentException("Wrong folder name {$name}");
        }

        return $this->dirBuilder->path($this->path)->name($name)->build();
    }

    /**
     * @inheritdoc
     */
    public function createChildFile(string $name): FileInterface
    {
        if (!preg_match('/^[a-z0-9_\.\-]*$/i', $name)) {
            throw new InvalidArgumentException("Wrong file name {$name}");
        }

        return $this->fileBuilder->path($this->path)->name($name)->build();
    }

    /**
     * @inheritdoc
     */
    public function findFilesByPattern(string $pattern): array
    {
        $listFiles = [];
        $regex = '/^' . implode('[^\/\.]+', array_map('preg_quote', explode('*', $pattern))) . '$/';

        foreach ($this->getIterator() as $file) {
            if ($file->isFile() && preg_match($regex, $file->getFileName())) {
                $listFiles[] = $this->createChildFile($file->getFilename());
            };
        }

        return $listFiles;
    }

    /**
     * Возвращает путь без имени каталога.
     *
     * @return string
     */
    public function getDirname(): string
    {
        return $this->info['dirname'];
    }

    /**
     * Возвращает имя каталога.
     *
     * @return string
     */
    public function getBasename(): string
    {
        return $this->info['basename'];
    }

    /**
     * Удаляет все содержимое каталога, сам каталог остается нетронутым.
     *
     * @return $this
     */
    public function empty(): DirectoryInterface
    {
        foreach ($this as $child) {
            $child->delete();
        }
        return $this;
    }
}