<?php

namespace Library\Mapper;

/**
 * Интерфейс для обьекта, который поясняет как извлечь данные ФИАС из XML
 * @package Library\Mapper
 */
interface XmlMapperInterface extends MapperInterface
{
    /**
     * Возвращает псевдо xpath к сущности внутри xml
     * @return string
     */
    public function getXmlPathRoot(): string;

    /**
     * Возвращает элемент сущности внутри xml
     * @return string
     */
    public function getXmlPathElement(): string;

    /**
     * Получает на вход строку с xml для сущности и извлекает из нее ассоциативный
     * массив с данными сущности.
     *
     * @param string $xml
     *
     * @return array
     */
    public function extractArrayFromXml(string $xml): array;
    /**
     * Возвращает маску имени файла, в котором хранятся данные для вставки.
     *
     * @return string
     */
    public function getInsertFileMask(): string;
    /**
     * Возвращает маску имени файла, в котором хранятся данные для удаления.
     *
     * @return string
     */
    public function getDeleteFileMask(): string;
}