<?php

namespace Library\Mapper;

/**
 * Интерфейс для обьекта, который возвращает список полей для сущности ФИАС.
 * Служит преждевсего для получения результатов из xml и записи их в базу данных
 * @package Library\Mapper
 */
interface MapperInterface
{
    /**
     * Возвращает список полей данной сущности
     * @param bool $low
     * @return array
     */
    public function getMap(bool $low = false): array;

    /**
     * Убирает из входящего массива все поля, ключей для которых нет в списке
     * полей для данного маппера.
     * @param array $messyArray
     * @param bool $low
     * @return array
     */
    public function mapArray(array $messyArray, bool $low = false): array;

    /**
     * Приводит значения к строковым представлениям.
     * Передав второй параметр true, приводит название полей в нижний регистр
     *
     * @param array $messyArray
     *
     * @param bool $low
     * @return array
     */
    public function convertToStrings(array $messyArray, bool $low = false): array;

    /**
     * Приводит значения к php представлениям.
     *
     * @param array $messyArray
     *
     * @param bool $low
     * @return array
     */
    public function convertToData(array $messyArray, bool $low = false): array;

}