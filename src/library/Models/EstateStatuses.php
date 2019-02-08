<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Статусы
 * @package Library\Models
 */
class EstateStatuses extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'ESTSTATID' => 'int',
        'NAME' => 'string',
    ];

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'ESTSTATID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'EstateStatuses';

    /**
     * @var string
     */
    protected $xmlPathElement = 'EstateStatus';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ESTSTAT_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ESTSTAT_*.XML';
}