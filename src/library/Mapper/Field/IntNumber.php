<?php

namespace Library\Mapper\Field;

use InvalidArgumentException;

/**
 * Целочисленный тип поля
 * @package Library\Mapper\Field
 */
class IntNumber implements FieldInterface {

    /**
     * Максимальная длина числа.
     *
     * @var int
     */
    protected $length = 10;
    /**
     * @param int $length
     */
    public function __construct(int $length = 10)
    {
        $this->length = $length;
    }
    /**
     * Возвращает максимальную длину числа.
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
    public function convertToString($input): string
    {
        return (string) $this->checkNumber((string) $input);
    }
    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function convertToData(string $input)
    {
        return (int) $this->checkNumber($input);
    }

    /**
     * Проверяет число согласно параметрам.
     *
     * @param string $input
     * @return string
     */
    protected function checkNumber(string $input): string
    {
        $input = trim($input);
        if (!preg_match('/^\d+$/', $input)) {
            throw new InvalidArgumentException(
                "String must contains only digits, got: {$input}"
            );
        }
        if ($this->length && mb_strlen($input) > $this->length) {
            throw new InvalidArgumentException(
                "String length must be less or equal than {$this->length}, got: {$input}"
            );
        }
        return $input;
    }
}