<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Типы комнат.
 * @package Library\Models
 */
class RoomTypes extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'RMTYPEID' => 'int',
        'NAME' => 'string',
        'SHORTNAME' => 'string',
    ];

    /**
     * @var string
     */
    protected $indexName = 'room_types';

    /**
     * @var string
     */
    protected $typeName = 'room_type';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'RMTYPEID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'RoomTypes';

    /**
     * @var string
     */
    protected $xmlPathElement = 'RoomType';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ROOMTYPE_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ROOMTYPE_*.XML';
}