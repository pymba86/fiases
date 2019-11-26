<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Версии
 * @package Library\Models
 */
class Versions extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'version' => 'string',
        'date' => 'string',
        'url' => 'string',
    ];

    /**
     * @var string
     */
    protected $indexName = 'versions';

    /**
     * @var string
     */
    protected $typeName = 'version';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'version';
}