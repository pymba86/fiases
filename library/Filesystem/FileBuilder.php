<?php

namespace Library\Filesystem;


/**
 * Стандартный построитель обьекта для работы с файлами
 * @package Library\Services\Filesystem
 */
class FileBuilder implements FileBuilderInterface
{

    /** @var string */
    private $path;

    /** @var string */
    private $name;

    /**
     * @inheritdoc
     */
    public function path(string $path): FileBuilderInterface
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function name(string $name): FileBuilderInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function build(): FileInterface
    {
        return new File($this->path . DIRECTORY_SEPARATOR . $this->name);
    }
}