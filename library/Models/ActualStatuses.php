<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Статусы
 * @package Library\Models
 */
class ActualStatuses extends AbstractMapper
{

    /**
     * @var mixed
     */
    protected $fields = [
        'ACTSTATID' => 'int',
        'NAME' => 'string',
    ];

    /**
     * @var string
     */
    protected $indexName = 'actual_statuses';

    /**
     * @var string
     */
    protected $typeName = 'actual_status';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'ACTSTATID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'ActualStatuses';

    /**
     * @var string
     */
    protected $xmlPathElement = 'ActualStatus';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ACTSTAT_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ACTSTAT_*.XML';
}