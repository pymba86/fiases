<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Дома.
 * @package Library\Models
 */
class Houses extends AbstractMapper
{

    /**
     * @var string
     */
    protected $indexName = 'houses';

    /**
     * @var mixed[]
     */
    protected $fields = [
        'HOUSEID' => 'uuid',
        'HOUSEGUID' => 'uuid',
        'AOGUID' => 'uuid',
        'REGIONCODE' => ['string', 2],
        'HOUSENUM' => ['string', 20],
        'STRSTATUS' => 'int',
        'ESTSTATUS' => 'int',
        'STATSTATUS' => 'int',
        'IFNSFL' => ['string', 4],
        'BUILDNUM' => ['string', 10],
        'CADNUM' => ['string', 100],
        'STRUCNUM' => ['string', 10],
        'TERRIFNSFL' => ['string', 4],
        'TERRIFNSUL' => ['string', 4],
        'IFNSUL' => ['string', 4],
        'OKATO' => ['string', 11],
        'OKTMO' => ['string', 11],
        'POSTALCODE' => ['string', 6],
        'STARTDATE' => 'date',
        'ENDDATE' => 'date',
        'UPDATEDATE' => 'date',
        'COUNTER' => 'int',
        'DIVTYPE' => 'int',
    ];

    /**
     * @var string
     */
    protected $typeName = 'house';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'HOUSEID';

    /**
     * @var int
     */
    protected $sqlPartitionsCount = 4;

    /**
     * @var string
     */
    protected $sqlPartitionField = 'HOUSEID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'Houses';

    /**
     * @var string
     */
    protected $xmlPathElement = 'House';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_HOUSE_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_HOUSE_*.XML';
}
