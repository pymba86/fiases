<?php

namespace Library\Filesystem;


/**
 * Стандартный построитель обьекта для работы с директорией
 * @package Library\Services\Filesystem
 */
class DirectoryBuilder implements DirectoryBuilderInterface
{

    /** @var string */
    private $path;

    /** @var string */
    private $name;

    /** @var FileBuilderInterface */
    private $fileBuilder;

    /** @var DirectoryBuilderInterface */
    private $dirBuilder;

    /**
     * @inheritdoc
     */
    public function path(string $path): DirectoryBuilderInterface
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function fileBuilder(FileBuilderInterface $builder): DirectoryBuilderInterface
    {
        $this->fileBuilder = $builder;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function dirBuilder(DirectoryBuilderInterface $builder): DirectoryBuilderInterface
    {
        $this->dirBuilder = $builder;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function name(string $name): DirectoryBuilderInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function build(): DirectoryInterface
    {
        $path = $this->path . DIRECTORY_SEPARATOR . $this->name;
        $dirBuilder = $this->dirBuilder ? $this->dirBuilder : new DirectoryBuilder();
        $fileBuilder = $this->fileBuilder ? $this->fileBuilder : new FileBuilder();
        return new Directory($path, $dirBuilder, $fileBuilder);
    }

}