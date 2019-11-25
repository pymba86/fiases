<?php

namespace Library\Xml;

use Iterator;
use Library\Mapper\XmlMapperInterface;

/**
 * Интерфейс для обьекта, который читате данные из файла xml
 * @package Library\Services\Xml
 */
interface ReaderInterface extends Iterator
{

    /**
     * Задает обьект-маппер, который описывает как извлечь целевой обьект из xml
     * @param XmlMapperInterface $mapper
     * @return ReaderInterface
     */
    public function setMapper(XmlMapperInterface $mapper): ReaderInterface;

    /**
     * Открываем файл для чтения, пытается найти путь указанный в маппере, если путь найден, то открываем файл
     * и возвращаем правду, если не найден, то возвращаем ложь
     * @param string $path Абсолютный путь к файлу, который нужно открыть
     * @return bool
     */
    public function openFile(string $path): bool;

    /**
     * Закрываем файл после чтения
     * @return void
     */
    public function closeFile(): void;
}