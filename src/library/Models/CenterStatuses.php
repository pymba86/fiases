<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Статусы
 * @package Library\Models
 */
class CenterStatuses extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'CENTERSTID' => 'int',
        'NAME' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'CENTERSTID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'CenterStatuses';

    /**
     * @var string
     */
    protected $xmlPathElement = 'CenterStatus';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_CENTERST_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_CENTERST_*.XML';
}