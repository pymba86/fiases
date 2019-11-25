<?php

namespace Library\Unpacker;

use Library\Filesystem\DirectoryInterface;
use Library\Filesystem\FileInterface;
use RuntimeException;

/**
 * Интерфейс для обьекта, который распоковывает данные из архива
 * @package Library\Services\Unpacker
 */
interface UnpackerInterface {

    /**
     * Извлекает данные из указанного в первом параметре архива по
     * указанному во втором параметре пути.
     *
     * @param FileInterface      $source
     * @param DirectoryInterface $destination
     *
     * @return void
     *
     * @throws RuntimeException
     */
    public function unpack(FileInterface $source, DirectoryInterface $destination);
}