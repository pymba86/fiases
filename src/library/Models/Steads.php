<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Земельные участки.
 * @package Library\Models
 */
class Steads extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'STEADGUID' => 'uuid',
        'NUMBER' => 'string',
        'REGIONCODE' => ['string', 2],
        'POSTALCODE' => ['string', 6],
        'IFNSFL' => ['string', 4],
        'IFNSUL' => ['string', 4],
        'OKATO' => ['string', 11],
        'OKTMO' => ['string', 11],
        'PARENTGUID' => 'uuid',
        'STEADID' => 'uuid',
        'OPERSTATUS' => 'string',
        'STARTDATE' => 'date',
        'ENDDATE' => 'date',
        'UPDATEDATE' => 'date',
        'LIVESTATUS' => 'string',
        'DIVTYPE' => 'string',
        'NORMDOC' => 'uuid',
    ];

    /**
     * @var string
     */
    protected $indexName = 'steads';

    /**
     * @var string
     */
    protected $typeName = 'stead';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'STEADGUID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'Steads';

    /**
     * @var string
     */
    protected $xmlPathElement = 'Stead';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_STEAD_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_STEAD_*.XML';
}