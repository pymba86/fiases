<?php

namespace Library\Mapper;


/**
 * Трэйт для объекта, который поясняет как правильно записать данные в базу данных.
 * @package Library\Mapper
 */
trait SqlMapperTrait
{
    use MapperTrait;

    /**
     * Имя индекса для данной сущности.
     *
     * Если не задано, то по умолчанию преобразует имя класса маппера.
     *
     * @var string|null
     */
    protected $indexName;

    /**
     * Имя типа для данной сущности.
     *
     * Если не задано, то по умолчанию преобразует имя класса маппера.
     *
     * @var string|null
     */
    protected $typeName;

    /**
     * Массив с названиями полей, которые должны быть использованы
     * в качестве первичного ключа.
     *
     * @var string
     */
    protected $sqlPrimary;

    /**
     * Возвращает имя индекса, в которой нужно будет сохранить данные.
     *
     * @return string
     */
    public function getIndexName(): string
    {
        $name = $this->indexName;
        if ($name === null) {
            $name = trim(str_replace('\\', '_', strtolower(get_class($this))), '_');
        }
        return $name;
    }

    /**
     * Возвращает имя типа, в которой нужно будет сохранить данные.
     *
     * @return string
     */
    public function getTypeName(): string
    {
        $name = $this->typeName;
        if ($name === null) {
            $name = trim(str_replace('\\', '_', strtolower(get_class($this))), '_');
        }
        return $name;
    }

    /**
     * Возвращает массив с названиями полей, которые должны быть использованы
     * в качестве первичного ключа.
     *
     * @return string[]
     */
    public function getPrimary(): string
    {
        return $this->sqlPrimary;
    }
}