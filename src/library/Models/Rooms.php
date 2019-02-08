<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Комнаты.
 * @package Library\Models
 */
class Rooms extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'ROOMID' => 'uuid',
        'ROOMGUID' => 'uuid',
        'HOUSEGUID' => 'uuid',
        'REGIONCODE' => ['string', 2],
        'FLATNUMBER' => ['string', 50],
        'FLATTYPE' => 'int',
        'POSTALCODE' => ['string', 6],
        'STARTDATE' => 'date',
        'ENDDATE' => 'date',
        'UPDATEDATE' => 'date',
        'OPERSTATUS' => 'string',
        'LIVESTATUS' => 'string',
        'NORMDOC' => 'uuid',
    ];

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'ROOMID';

    /**
     * @var int
     */
    protected $sqlPartitionsCount = 4;

    /**
     * @var string
     */
    protected $sqlPartitionField = 'ROOMID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'Rooms';

    /**
     * @var string
     */
    protected $xmlPathElement = 'Room';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ROOM_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ROOM_*.XML';
}