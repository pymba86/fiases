<?php

namespace Library\Mapper\Field;

/**
 * Интерфейс для поля сущности
 * @package Library\Mapper\Field
 */
interface FieldInterface
{
    /**
     * Конвертирует входящий параметр к типу, соответсвующему данному полю.
     *
     * @param string $input
     *
     * @return mixed
     */
    public function convertToData(string $input);
    /**
     * Конвертирует входящий параметр к строке, для записи в БД.
     *
     * @param mixed $input
     *
     * @return string
     */
    public function convertToString($input): string;
}