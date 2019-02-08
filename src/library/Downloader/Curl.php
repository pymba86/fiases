<?php

namespace Library\Downloader;

use Library\Filesystem\FileInterface;
use RuntimeException;

/**
 * Обьект, который скачивает файл по ссылке с помощью curl
 * @package Library\Services\Downloader
 */
class Curl implements DownloaderInterface {

    /**
     * @inheritdoc
     */
    public function download(string $urlToDownload, FileInterface $localFile)
    {
        $fh = $this->openLocalFile($localFile);
        $requestOptions = [
            CURLOPT_URL => $urlToDownload,
            CURLOPT_FILE => $fh,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_FRESH_CONNECT => true,
        ];
        list($res, $httpCode, $error) = $this->curlDownload($requestOptions);
        fclose($fh);
        if ($res === false) {
            throw new RuntimeException("Error while downloading: {$error}");
        } elseif ($httpCode !== 200) {
            throw new RuntimeException("Url returns status: {$httpCode}");
        }
    }

    /**
     * Загружает файл по ссылке в указанный файл
     * @param array $requestOptions
     * @return array
     */
    protected function curlDownload(array $requestOptions)
    {
        $ch = curl_init();
        curl_setopt_array($ch, $requestOptions);
        $res = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        return [$res, $httpCode, $error];
    }

    /**
     * Открывает локальный файл, в который будет вестить запись и возвращает его ресурс
     * @param FileInterface $localFile
     * @return bool|resource
     */
    protected function openLocalFile(FileInterface $localFile)
    {
        $hLocal = @fopen($localFile->getPath(), 'wb');
        if ($hLocal === false) {
            throw new RuntimeException(
                "Can't open local file for writing: " . $localFile->getPath()
            );
        }
        return $hLocal;
    }
}