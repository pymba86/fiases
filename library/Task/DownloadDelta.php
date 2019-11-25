<?php

namespace Library\Task;

use InvalidArgumentException;
use Library\Informer\InformerInterface;
use Library\Informer\InformerResultInterface;
use Library\State\StateInterface;

/**
 * Задача для загрузки архива с изменениями ФИАС относительно указанной версии
 * @package Library\Task
 */
class DownloadDelta extends DownloadFull
{

    /**
     * Получает ссылку на файл с изменениями в базе относительно версии из сервиса ФИАС
     * @return InformerResultInterface
     */
    protected function fetchInfoFromService(): InformerResultInterface
    {
        $currentVersion = (int)$this->informerResult->getVersion();

        $this->info("Извлечение текущей версии архива " .
            "дельты из архива {$currentVersion} информационной службы ФИАС");

        /** @var InformerInterface $informer */
        $informer = $this->di->get("informer");

        return $informer->getDeltaInfo($currentVersion);
    }


}