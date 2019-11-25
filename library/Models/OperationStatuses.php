<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Статусы.
 * @package Library\Models
 */
class OperationStatuses extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'OPERSTATID' => 'int',
        'NAME' => 'string',
    ];

    /**
     * @var string
     */
    protected $indexName = 'operation_statuses';

    /**
     * @var string
     */
    protected $typeName = 'operation_status';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'OPERSTATID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'OperationStatuses';

    /**
     * @var string
     */
    protected $xmlPathElement = 'OperationStatus';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_OPERSTAT_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_OPERSTAT_*.XML';
}