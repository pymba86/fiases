<?php


namespace Library\Filesystem;


/**
 * Интерфейс для обьекта, который cтроит обьекты для работы с файлами
 * @package Library\Services\Filesystem
 */
interface FileBuilderInterface
{
    /**
     * Устанавливает путь до файла
     * @param string $path
     * @return FileBuilderInterface
     */
    public function path(string $path): FileBuilderInterface;

    /** Установить имя файла
     * @param string $name
     * @return FileBuilderInterface
     */
    public function name(string $name): FileBuilderInterface;

    /**
     * Построить файл
     * @return FileInterface
     */
    public function build(): FileInterface;
}