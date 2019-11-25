<?php

namespace Library\Search;


use Library\Mapper\SqlMapperInterface;

/**
 * Интерфейс для обьекта, который реализует взаимодействие с поисковым движком
 * @package Library\Search
 */
interface ConnectionInterface
{
    /**
     * Сохраняет новую строку в движке
     * В случае если она там уже есть, перезаписывает
     *
     * @param SqlMapperInterface $mapper
     * @param array $item
     * @return mixed
     */
    public function save(SqlMapperInterface $mapper, array $item);

    /**
     * Удаляет строку из движка
     * @param SqlMapperInterface $mapper
     * @param array $item
     * @return mixed
     */
    public function delete(SqlMapperInterface $mapper, array $item);

    /**
     * Поиск по описанию
     *
     * @param array $options
     * @return mixed
     */
    public function find(array $options);

    /**
     * Создает структуру для маппера
     *
     * @param SqlMapperInterface $mapper
     * @return mixed
     */
    public function create(SqlMapperInterface $mapper);

    /**
     * Удаляет структуру для маппера
     *
     * @param SqlMapperInterface $mapper
     * @return mixed
     */
    public function drop(SqlMapperInterface $mapper);

    /**
     * Указывает, что данный потребитель закончил работу с движком и нужно
     * дописать все оставшиеся запросы и сбросить все временные данные.
     *
     * @return void
     */
    public function complete();
}