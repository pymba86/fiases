<?php


use Library\Providers\CacheDataProvider;
use Library\Providers\ConfigProvider;
use Library\Providers\DatabaseProvider;
use Library\Providers\ErrorHandlerProvider;
use Library\Providers\LoggerProvider;
use Library\Providers\ModelsMetadataProvider;
use Library\Providers\RequestProvider;
use Library\Providers\ResponseProvider;
use Library\Providers\RouterProvider;

/**
 * Список сервисов, которые будут зарегистрированы в приложении API
 */
return [
    ConfigProvider::class,
    LoggerProvider::class,
    ErrorHandlerProvider::class,
    DatabaseProvider::class,
    ModelsMetadataProvider::class,
    RequestProvider::class,
    ResponseProvider::class,
    RouterProvider::class,
    CacheDataProvider::class,
];


