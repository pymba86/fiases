<?php

namespace Library\Mapper\Field;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use Exception;


class Date implements FieldInterface {

    /**
     * Формат даты
     * @var string
     */
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function convertToData(string $input)
    {
        return new DateTime($input);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function convertToString($input): string
    {
        if (!($input instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Field value must be a DateTimeInterface to convert to string'
            );
        }
        return $input->format(self::DATE_FORMAT);
    }
}