<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Типы квартир.
 * @package Library\Models
 */
class FlatTypes extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'FLTYPEID' => 'int',
        'NAME' => 'string',
        'SHORTNAME' => 'string',
    ];

    /**
     * @var string
     */
    protected $indexName = 'flat_types';

    /**
     * @var string
     */
    protected $typeName = 'flat_type';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'FLTYPEID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'FlatTypes';

    /**
     * @var string
     */
    protected $xmlPathElement = 'FlatType';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_FLATTYPE_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_FLATTYPE_*.XML';
}