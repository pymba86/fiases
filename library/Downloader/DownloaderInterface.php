<?php

namespace Library\Downloader;

use Library\Filesystem\FileInterface;

/**
 * Интерфейс для обьекта, который скачивает файл по ссылке
 * @package Library\Services\Downloader
 */
interface DownloaderInterface {


    /**
     * Скачивает файл по ссылке из первого параметра в локальный файл, указанный во втором параметре
     * @param string $urlToDownload
     * @param FileInterface $localFile
     * @return void
     */
    public function download(string $urlToDownload, FileInterface $localFile): void;
}