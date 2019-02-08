<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Статусы.
 * @package Library\Models
 */
class IntervalStatuses extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'INTVSTATID' => 'int',
        'NAME' => 'string',
    ];

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'INTVSTATID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'IntervalStatuses';

    /**
     * @var string
     */
    protected $xmlPathElement = 'IntervalStatus';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_INTVSTAT_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_INTVSTAT_*.XML';
}