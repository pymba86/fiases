<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Статусы.
 * @package Library\Models
 */
class StructureStatuses extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'STRSTATID' => 'int',
        'NAME' => 'string',
        'SHORTNAME' => 'string',
    ];

    /**
     * @var string
     */
    protected $indexName = 'structure_statuses';

    /**
     * @var string
     */
    protected $typeName = 'structure_status';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'STRSTATID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'StructureStatuses';

    /**
     * @var string
     */
    protected $xmlPathElement = 'StructureStatus';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_STRSTAT_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_STRSTAT_*.XML';
}