<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Статусы
 * @package Library\Models
 */
class CurrentStatuses extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'CURENTSTID' => 'int',
        'NAME' => 'string',
    ];

    /**
     * @var string
     */
    protected $indexName = 'current_statuses';

    /**
     * @var string
     */
    protected $typeName = 'current_status';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'CURENTSTID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'CurrentStatuses';

    /**
     * @var string
     */
    protected $xmlPathElement = 'CurrentStatus';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_CURENTST_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_CURENTST_*.XML';
}