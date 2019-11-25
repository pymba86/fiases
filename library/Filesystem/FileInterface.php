<?php

namespace Library\Filesystem;


/**
 * Интерфейс для обьекта, который инкапсулирует обращение к файлу в локальной файловой системе
 * @package Library\Services\Filesystem
 */
interface FileInterface extends CleanableInterface
{
    /**
     * Возвращает путь без имени файла.
     * @return string
     */
    public function getDirname(): string;

    /**
     * Возвращает путь и имя файла.
     * @return string
     */
    public function getPath(): string;

    /**
     * Возвращает имя файла без расширения
     * @return string
     */
    public function getFileName(): string;

    /**
     * Возвращает расширение файла
     * @return string
     */
    public function getExtension(): string;

    /**
     * Возвращает полное имя файла (с расширением)
     * @return string
     */
    public function getBaseName(): string;

}