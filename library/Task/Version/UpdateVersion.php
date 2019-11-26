<?php

namespace Library\Task\Version;

use Library\Informer\InformerResultInterface;
use Library\Models\Versions;
use Library\Search\ConnectionInterface;
use Library\State\StateInterface;
use Library\Task\AbstractTask;

/**
 * Задача, которая обновляет версию установленной версии ФИАС
 * @package Library\Task
 */
class UpdateVersion extends AbstractTask
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
        $this->info("Сохраняем установленную версию");

        /** @var ConnectionInterface $search */
        $search = $this->di->getShared('search');

        $search->save(new Versions(), [
                'version' => strval($this->informerResult->getVersion()),
                'url' => $this->informerResult->getUrl(),
                'data' => time()
            ]
        );

        $this->info("Сохранение прошло успешно");
    }
}