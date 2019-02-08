<?php


namespace Library\State;

/**
 * Интерфейс для объекта, который передает состояние между операциями.
 * @package Library\Bag
 */
interface StateInterface {

    /**
     * Задает именованный параметр для состояния
     * @param string $name
     * @param mixed $value
     * @return StateInterface
     */
    public function set(string $name, $value): StateInterface;

    /**
     * Возвращает параметр для состояния по имени
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * Команда, которая отмечает, что нужно мякгко прервать цепочку операций
     * @return StateInterface
     */
    public function complete(): StateInterface;

    /**
     * Метод, который указывает, что цепочка должна быть прервана после текущей операции
     * @return bool
     */
    public function isCompleted(): bool;

}