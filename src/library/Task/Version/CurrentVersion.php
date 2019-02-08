<?php

namespace Library\Task\Version;

use Library\Informer\InformerResultInterface;
use Library\State\StateInterface;
use Library\Task\AbstractTask;

/**
 * Задача, которая получает установленную версии ФИАС
 * @package Library\Task
 */
class CurrentVersion extends AbstractTask
{
    /**
     * Содержит результат со ссылкой на базу
     * @var InformerResultInterface
     */
    protected $informerResult;

    /**
     * Сохраняет версию ФИАС
     * @param InformerResultInterface $informerResult
     */
    public function __construct(InformerResultInterface $informerResult)
    {
        $this->informerResult = $informerResult;
    }

    /**
     * @inheritdoc
     */
    public function run(StateInterface $state): void
    {
        $this->info("Получение установленной версии");

        //TODO Получение версии из базы/файла

        $this->info("Версия определена");
    }
}