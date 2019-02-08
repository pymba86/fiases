<?php

namespace Library\Task;

use Exception;
use Library\Downloader\DownloaderInterface;
use Library\Filesystem\FileInterface;
use Library\Informer\InformerInterface;
use Library\Informer\InformerResultInterface;
use Library\State\StateInterface;

/**
 * Задача для загрузки архива с полной версией ФИАС.
 * @package Library\Task
 */
class DownloadFull extends AbstractTask
{
    /**
     * Файл куда будет загружаться база
     * @var FileInterface
     */
    protected $file;

    /**
     * Содержит результат со ссылкой на базу
     * @var InformerResultInterface
     */
    protected $informerResult;

    /**
     * @param string $file
     * @param InformerResultInterface $informerResult
     */
    public function __construct(string $file, InformerResultInterface $informerResult)
    {
        $this->file = $file;
        $this->informerResult = $informerResult;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function run(StateInterface $state): void
    {
        $informerResult = $this->fetchInfoFromService();

        if ($informerResult->hasResult()) {
            $this->informerResult = $informerResult;
            $this->downloadFile($informerResult->getUrl());
        } else {
            $this->info('Получить ссылку не получилось');
            $state->complete();
        }
    }

    /**
     * Получает ссылку на файл с полной базой данных из сервиса ФИАС.
     *
     * @return InformerResultInterface
     */
    protected function fetchInfoFromService(): InformerResultInterface
    {
        $this->info('Извлечение URL-адреса архива из информационной службы ФИАС');

        /** @var InformerInterface $informer */
        $informer = $this->di->get("informer");

        return $informer->getCompleteInfo();
    }

    /**
     * Загружает файл по ссылке.
     *
     * @param string $url
     * @return void
     * @throws Exception
     */
    protected function downloadFile(string $url): void
    {
        $this->info("Url получен: {$url}");

        /** @var DownloaderInterface $downloader */
        $downloader = $this->di->get("downloader");

        try {

            $this->info("Загрузка файла из {$url} в " . $this->file->getPath());
            $downloader->download($url, $this->file);
            $this->info('Загрузка заверщена ' . $this->file->getPath());

        } catch (Exception $e) {

            $this->info('Загрузка прервана, удаляем ' . $this->file->getPath());
            $this->file->delete();
            throw $e;

        }
    }
}