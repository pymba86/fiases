<?php

namespace Library\Filesystem;


/**
 * Интерфейс для обьекта, который содержит возможность удаления из файловой системы
 * @package Library\Filesystem
 */
interface CleanableInterface
{
    /**
     * Возвращает true, если существует в файловой системе
     * @return bool
     */
    public function isExists(): bool ;

    /**
     * Удаляет из файловой системы
     * @return bool
     */
    public function delete();

}