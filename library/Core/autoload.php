<?php

use Phalcon\Loader;

use function Library\Core\appPath;

require __DIR__ . '/functions.php';


// Регистрируем классы
$loader = new Loader();

$namespaces = [
    'Library' => appPath('library'),
    'Library\Api\Controllers' => appPath('api/controllers'),
    'Library\Cli\Task' => appPath('cli/tasks'),
    'Library\Tests' => appPath('tests')
];

$loader->registerNamespaces($namespaces);
$loader->register();

// Composer Autoloader
# require appPath('vendor/autoload.php');


// Загрузка окружения
//$env = Dotenv::create(appPath());
//$env->overload();
