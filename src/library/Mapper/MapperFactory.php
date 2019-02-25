<?php

namespace Library\Mapper;

use Library\Mapper\Field\FieldInterface;

/**
 * Абстрактная фабрика для маппера
 * @package Library\Mapper
 */
class MapperFactory {

    /** @var array */
    protected $options;

    protected function build(string $descriptionField): FieldInterface {

    }
}