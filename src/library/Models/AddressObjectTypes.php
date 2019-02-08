<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Типы объектов в адресах.
 * @package Library\Models
 */
class AddressObjectTypes extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'KOD_T_ST' => ['int', 4],
        'LEVEL' => ['int', 4],
        'SOCRNAME' => 'string',
        'SCNAME' => 'string',
    ];

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'KOD_T_ST';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'AddressObjectTypes';

    /**
     * @var string
     */
    protected $xmlPathElement = 'AddressObjectType';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_SOCRBASE_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_SOCRBASE_*.XML';
}