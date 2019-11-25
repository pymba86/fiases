<?php

namespace Library\Mapper\Field;

use InvalidArgumentException;

/**
 * Поле с uuid
 * @package Library\Mapper\Field
 */
class Uuid extends Line
{

    /**
     * Длина uuid
     * @var int
     */
    const LIGHT = 36;

    /**
     * Констуктор поля с uuid
     */
    public function __construct()
    {
        parent::__construct(self::LIGHT);
    }

    /**
     * Проверяет, что значение является валидным uuid.
     *
     * @param mixed $input
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function checkString($input): string
    {
        $input = (string)$input;
        if ($input !== '' && !preg_match('/^[a-z0-9]{8}\-[a-z0-9]{4}\-[a-z0-9]{4}\-[a-z0-9]{4}\-[a-z0-9]{12}$/i', $input)) {
            throw new InvalidArgumentException(
                "String must be valid uuid, got: {$input}"
            );
        }
        return $input;
    }
}