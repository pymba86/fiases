<?php

namespace Library\Task;

use Library\Console\Logger;
use Phalcon\DiInterface;

abstract class AbstractTask implements TaskInterface
{

    /** @var DiInterface */
    protected $di;

    /**
     * Установить зависимости
     * @param DiInterface $di
     */
    public function setDI(DiInterface $di)
    {
        $this->di = $di;
    }

    /**
     * Получить зависимости
     * @return DiInterface|null
     */
    public function getDI()
    {
        return $this->di;
    }


    protected function info(string $message, array $context = [])
    {
        /** @var Logger $logger */
        $this->di->get("logger")->info($message);
    }

}