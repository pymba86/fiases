<?php


use Library\Providers\CacheDataProvider;
use Library\Providers\CliDispatcherProvider;
use Library\Providers\ConfigProvider;
use Library\Providers\ConsoleLogProvider;
use Library\Providers\DatabaseProvider;
use Library\Providers\DownloaderProvider;
use Library\Providers\FilesystemProvider;
use Library\Providers\InformerProvider;
use Library\Providers\ModelsMetadataProvider;
use Library\Providers\ReaderProvider;
use Library\Providers\UnpackerProvider;
use Library\Providers\SearchProvider;

/**
 * Список сервисов, которые будут зарегистрированы в приложении CLI
 */
return [
    ConfigProvider::class,
    ConsoleLogProvider::class,
    InformerProvider::class,
    FilesystemProvider::class,
    ReaderProvider::class,
    DownloaderProvider::class,
    DatabaseProvider::class,
    ModelsMetadataProvider::class,
    CliDispatcherProvider::class,
    CacheDataProvider::class,
    UnpackerProvider::class,
    SearchProvider::class
];
