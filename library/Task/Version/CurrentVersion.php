<?php

namespace Library\Task\Version;

use Exception;
use Library\Informer\InformerResultInterface;
use Library\Search\ConnectionInterface;
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
        $this->info("Версия определена: " . $this->informerResult->getVersion());
        $this->info("Получение установленной версии");

        /** @var ConnectionInterface $search */
        $search = $this->di->getShared('search');

        try {

            $installVersion = $search->find([
                'index' => 'versions',
                'type' => 'version',
                'limit' => 10000,
                'fields' => ['version'],
            ]);

            $versions = json_decode(json_encode($installVersion), True);
            $items = $versions['items'];

            if (isset($items)) {

                $equalsVersion = array_filter($items, function ($item) {
                   return ($item['version'] == $this->informerResult->getVersion());
                });

                if (count($equalsVersion) > 0) {
                    $this->info('Удаленная версия уже установлена');
                    $state->complete();
                }

            } else {
                $this->info('Нет установленных версий');
            }

        } catch (Exception $exception) {
            $this->info('Текущая версия: не определена');
            $this->info($exception->getMessage());
        }
    }
}