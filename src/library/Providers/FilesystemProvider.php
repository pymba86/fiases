<?php

namespace Library\Providers;

use Library\Filesystem\Directory;
use Library\Filesystem\DirectoryBuilder;
use Library\Filesystem\FileBuilder;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

use function Library\Core\envValue;
use function Library\Core\appPath;

class FilesystemProvider implements ServiceProviderInterface
{

    /**
     * @inheritdoc
     */
    public function register(DiInterface $container)
    {
        $container->setShared(
            'fs',
            function () {

                $dirBuilder = new DirectoryBuilder();
                $fileBuilder = new FileBuilder();
                $path = envValue('FILESYSTEM_DEFAULT_PATH', appPath('storage'));

                return new Directory($path, $dirBuilder, $fileBuilder);
            });
    }
}