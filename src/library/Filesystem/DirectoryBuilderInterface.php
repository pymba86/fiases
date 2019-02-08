<?php

namespace Library\Filesystem;

/**
 * Интерфейс для обьекта, который cтроит обьекты для работы с директориями
 * @package Library\Services\Filesystem
 */
interface DirectoryBuilderInterface
{

    /**
     * Устанавливает путь до директории
     * @param string $path
     * @return DirectoryBuilderInterface
     */
    public function path(string $path): DirectoryBuilderInterface;

    /** Установить строителя для директории
     * @param DirectoryBuilderInterface $builder
     * @return DirectoryBuilderInterface
     */
    public function dirBuilder(DirectoryBuilderInterface $builder): DirectoryBuilderInterface;

    /** Установить имя директории
     * @param string $name
     * @return DirectoryBuilderInterface
     */
    public function name(string $name): DirectoryBuilderInterface;

    /** Установить строителя для файлов
     * @param FileBuilderInterface $builder
     * @return DirectoryBuilderInterface
     */
    public function fileBuilder(FileBuilderInterface $builder): DirectoryBuilderInterface;

    /**
     * Построить директорию
     * @return DirectoryInterface
     */
    public function build(): DirectoryInterface;

}