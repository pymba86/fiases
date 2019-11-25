<?php

namespace Library\Mapper;

use Exception;
use Library\Mapper\Field\Date;
use Library\Mapper\Field\FieldInterface;
use Library\Mapper\Field\IntNumber;
use Library\Mapper\Field\Line;
use Library\Mapper\Field\Uuid;
use UnexpectedValueException;
use ReflectionClass;

/**
 * Трэйт для объекта, который возвращает список полей для сущности ФИАС.
 * Служит преждевсего для получения результатов из xml и записи их в базу данных.
 * @package Library\Mapper
 */
trait MapperTrait
{
    /**
     * Возвращает массив с описанием полей сущности.
     *
     * Массив состоит из нулевого элемента - имени класса поля и неограниченного
     * количества других элементов, который будут переданы в качестве аргументов
     * конструктора.
     *
     * @var mixed[]
     */
    protected $fields = [];

    /**
     * Массив инициированных полей сущности.
     *
     * Для полей настрена отложенная инициализация, данный массив является служебным
     * для реализации отложенной инициализации.
     *
     * @var array|null
     */
    protected $initializedFields;

    /**
     * Массив псевдонимов для классов полей.
     *
     * @var string[]
     */
    protected $fieldsAliases = [
        'string' => Line::class,
        'date' => Date::class,
        'int' => IntNumber::class,
        'uuid' => Uuid::class,
    ];

    /**
     * Возвращает список полей данной сущности.
     *
     * @param bool $low
     * @return FieldInterface[]
     *
     * @throws \ReflectionException
     */
    public function getMap(bool $low = false): array
    {
        if (!is_array($this->initializedFields)) {
            $this->initializedFields = [];
            foreach ($this->fields as $fieldName => $fieldDescription) {
                if (!is_array($fieldDescription)) {
                    $fieldDescription = [$fieldDescription];
                }
                $fieldName = $low ? strtolower($fieldName) : $fieldName;
                $this->initializedFields[$fieldName] = $this->initializeField($fieldDescription);
            }
        }
        return $this->initializedFields;
    }

    /**
     * Убирает из входящего массива все поля, ключей для которых нет в списке
     * полей для данного маппера.
     *
     * @param array $messyArray
     *
     * @param bool $low
     * @return array
     *
     * @throws \ReflectionException
     */
    public function mapArray(array $messyArray, bool $low = false): array
    {
        $map = $this->getMap($low);
        $mappedArray = [];
        foreach ($map as $fieldName => $field) {
            $fieldName = $low ? strtolower($fieldName) : $fieldName;
            $mappedArray[$fieldName] = $messyArray[$fieldName] ?? null;
        }
        return $mappedArray;
    }

    /**
     * Приводит значения к строковым представлениям.
     * Передав второй параметр true, приводит название полей в нижний регистр
     *
     * @param array $messyArray
     *
     * @param bool $low
     * @return array
     *
     * @throws \ReflectionException
     */
    public function convertToStrings(array $messyArray, bool $low = false): array
    {
        $map = $this->getMap($low);
        $convertedArray = [];
        foreach ($messyArray as $fieldName => $value) {
            try {
                $fieldName = $low ? strtolower($fieldName) : $fieldName;
                $convertedArray[$fieldName] = isset($map[$fieldName])
                    ? $map[$fieldName]->convertToString($value)
                    : $value;
            } catch (Exception $e) {
                throw new UnexpectedValueException(
                    "Convert to string error, field {$fieldName}. " . $e->getMessage()
                );
            }
        }
        return $convertedArray;
    }

    /**
     * Приводит значения к php представлениям.
     *
     * @param array $messyArray
     *
     * @param bool $low
     * @return array
     *
     * @throws \ReflectionException
     */
    public function convertToData(array $messyArray, bool $low = false): array
    {
        $map = $this->getMap($low);
        $convertedArray = [];
        foreach ($messyArray as $fieldName => $value) {
            try {
                $fieldName = $low ? strtolower($fieldName) : $fieldName;
                $convertedArray[$fieldName] = isset($map[$fieldName])
                    ? $map[$fieldName]->convertToData($value)
                    : $value;
            } catch (Exception $e) {
                throw new UnexpectedValueException(
                    "Convert to data error, field {$fieldName}. " . $e->getMessage()
                );
            }
        }
        return $convertedArray;
    }

    /**
     * Инициирует объект поля по его описанию.
     * TODO Сделать абстрактную фабрику для полей вместо рефлексии
     * @param array $init
     *
     * @return FieldInterface
     *
     * @throws UnexpectedValueException
     * @throws \ReflectionException
     */
    protected function initializeField(array $init): FieldInterface
    {
        $class = array_shift($init);
        if (isset($this->fieldsAliases[$class])) {
            $class = $this->fieldsAliases[$class];
        }
        $reflection = new ReflectionClass($class);
        $object = $reflection->newInstanceArgs($init);
        if (!($object instanceof FieldInterface)) {
            throw new UnexpectedValueException(
                'Field must be instance of ' . FieldInterface::class . " got {$class}"
            );
        }
        return $object;
    }
}