<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Статусы.
 * @package Library\Models
 */
class HouseStateStatuses extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'HOUSESTID' => 'int',
        'NAME' => 'string',
    ];

    /**
     * @var string
     */
    protected $indexName = 'house_state_statuses';

    /**
     * @var string
     */
    protected $typeName = 'house_state_status';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'HOUSESTID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'HouseStateStatuses';

    /**
     * @var string
     */
    protected $xmlPathElement = 'HouseStateStatus';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_HSTSTAT_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_HSTSTAT_*.XML';
}