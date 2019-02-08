<?php

namespace Library\Filesystem;

use Iterator;

/**
 * Обьект, который инкапсулирует обращение к папке в локальной файловой системе
 * @package Library\Services\Filesystem
 */
interface DirectoryInterface extends Iterator, CleanableInterface
{
    /**
     * Возвращает путь и имя каталога.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Возвращает путь без имени каталога.
     *
     * @return string
     */
    public function getDirname(): string;

    /**
     * Возвращает имя каталога.
     *
     * @return string
     */
    public function getBasename(): string;

    /**
     * Создает каталог и все родительские каталоги, если потребуется.
     *
     * @return $this
     */
    public function create(): DirectoryInterface;

    /**
     * Удаляет все содержимое каталога, сам каталог остается нетронутым.
     *
     * @return $this
     */
    public function empty(): DirectoryInterface;

    /**
     * Создает вложенный каталог.
     *
     * @param string $name
     *
     * @return DirectoryInterface
     */
    public function createChildDirectory(string $name): DirectoryInterface;

    /**
     * Создает вложенный файл.
     *
     * @param string $name
     *
     * @return FileInterface
     */
    public function createChildFile(string $name): FileInterface;

    /**
     * Ищет файл в текущей папке по указанному паттерну.
     *
     * @param string $pattern
     *
     * @return FileInterface[]
     */
    public function findFilesByPattern(string $pattern): array;
}