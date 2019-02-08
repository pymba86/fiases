<?php

namespace Library\Mapper;


/**
 * Интерфейс для обьекта, который поясняет как правильно записать данные в базу данных
 * @package Library\Mapper
 */
interface SqlMapperInterface extends MapperInterface
{
    /**
     * Возвращает имя индекса, в которой нужно будет сохранить данные.
     *
     * @return string
     */
    public function getIndexName(): string;

    /**
     * Возвращает имя типа, в которой нужно будет сохранить данные.
     *
     * @return string
     */
    public function getTypeName(): string;

    /**
     * Возвращает массив с названиями полей, которые должны быть использованы
     * в качестве первичного ключа.
     *
     * @return string[]
     */
    public function getPrimary(): string ;

}