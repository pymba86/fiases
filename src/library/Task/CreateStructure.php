<?php

namespace Library\Task;

use Library\Mapper\AbstractMapper;
use Library\Search\ConnectionInterface;
use Library\State\StateInterface;

/**
 * Создание структуры для указанного маппера
 * @package Library\Task\Data
 */
class CreateStructure extends AbstractTask
{

    /** @var AbstractMapper */
    protected $mapper;

    /**
     * Создание структуры для переданного маппера
     * @param AbstractMapper $mapper
     */
    public function __construct(AbstractMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @inheritdoc
     */
    public function run(StateInterface $state): void
    {
        /** @var ConnectionInterface $search */
        $search = $this->di->get("search");

         $this->info('Удаляем старую структуру для ' . $this->mapper->getIndexName());
         $search->drop($this->mapper);

         $this->info('Создаем новую структуру для ' . $this->mapper->getIndexName());
         $search->create($this->mapper);

         $this->info('Структура для ' . $this->mapper->getIndexName() . ' успешно создана');

    }
}