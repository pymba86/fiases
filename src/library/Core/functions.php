<?php

namespace Library\Core;

use function function_exists;
use function getenv;

if (function_exists('Library\Core\appPath') == !true) {

    /**
     * Получить путь приложения
     * @param string $path
     * @return string
     */
    function appPath(string $path): string
    {
        return dirname(dirname(__DIR__)) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (function_exists('Library\Core\envValue') == !true) {

    /**
     * Получить значение переменных окружения. Если значения нет, возращает значение по умолчанию
     * @param string $variable
     * @param null $default
     * @return array|false|mixed|string|null
     */
    function envValue(string $variable, $default = null)
    {
        $return = $default;
        $value  = getenv($variable);
        $values = [
            'false' => false,
            'true'  => true,
            'null'  => null,
        ];
        if (false !== $value) {
            $return = $values[$value] ?? $value;
        }
        return $return;
    }
}

if (function_exists('Library\Core\appUrl') == !true) {

    /**
     * Создать url путь до ресурса
     * @param string $resource
     * @param int $recordId
     * @return string
     */
    function appUrl(string $resource, int $recordId)
    {
        return sprintf(
            '%s/%s/%s',
            envValue('APP_URL'),
            $resource,
            $recordId
        );
    }
}


