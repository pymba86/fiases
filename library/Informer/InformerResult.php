<?php

namespace Library\Informer;

use InvalidArgumentException;

/**
 * Объект, который предоставляет результат со ссылкой на файлы
 * от сервиса ФИАС.
 * @package Library\Informer
 */
class InformerResult implements InformerResultInterface
{

    /**
     * @var int
     */
    protected $version = 0;
    /**
     * @var string
     */
    protected $url = '';

    /**
     * @inheritdoc
     */
    public function setVersion(int $version): InformerResultInterface
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @inheritdoc
     */
    public function setUrl(string $url): InformerResultInterface
    {
        if (!preg_match('#https?\://.+\.[^\.]+.*#', $url)) {
            throw new InvalidArgumentException("Wrong url format: {$url}");
        }
        $this->url = $url;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritdoc
     */
    public function hasResult(): bool
    {
        return $this->url !== '' && $this->version !== 0;
    }
}