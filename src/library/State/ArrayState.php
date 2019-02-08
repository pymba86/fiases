<?php

namespace Library\State;

/**
 * Объект, который хранит состояние во внутреннем массиве.
 * @package Library\Bag
 */
class ArrayState implements StateInterface
{

    /** @var array */
    private $parameters = [];

    /** @var bool */
    private $isCompleted = false;

    /**
     * @inheritdoc
     */
    public function set(string $name, $value): StateInterface
    {
        $name = $this->unify($name);
        $this->parameters[$name] = $value;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get(string $name, $default = null)
    {
        $name = $this->unify($name);
        return isset($this->parameters[$name]) ? $this->parameters[$name] : $default;
    }

    /**
     * @inheritdoc
     */
    public function complete(): StateInterface
    {
        $this->isCompleted = true;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    /**
     * Приводит имя параметра к общему виду, чтобы не плодить разные варианты имен
     * @param string $name
     * @return string
     */
    public function unify(string $name): string
    {
        return preg_replace('/[^a-z0-9_]+/', '_', strtolower(trim($name)));
    }
}