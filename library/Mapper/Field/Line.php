<?php

namespace Library\Mapper\Field;

use InvalidArgumentException;

/**
 * Строковый тип поля
 * @package Library\Mapper\Field
 */
class Line implements FieldInterface {

    /**
     * Максимальная длина строки.
     *
     * @var int
     */
    protected $length = 255;
    /**
     * @param int $length
     */
    public function __construct(int $length = 255)
    {
        $this->length = $length;
    }
    /**
     * Возвращает максимальную длину строки.
     *
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }
    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function convertToData(string $input): string
    {
        return $this->checkString($input);
    }
    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function convertToString($input): string
    {
        return $this->checkString($input);
    }

    /**
     * Проверяет, чтобы указанная строка была не длинее, чем указано в настройках
     * поля.
     *
     * @param mixed $input
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function checkString($input): string
    {
        $input = (string) $input;
        if ($this->length && mb_strlen($input) > $this->length) {
            throw new InvalidArgumentException(
                "String length must be less or equal than {$this->length}, got: {$input}"
            );
        }
        return $input;
    }
}