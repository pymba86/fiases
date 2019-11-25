<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Адреса
 * @package Library\Models
 */
class AddressObjects extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'AOID' => 'uuid',
        'AOGUID' => 'uuid',
        'PARENTGUID' => 'uuid',
        'NEXTID' => 'uuid',
        'FORMALNAME' => 'string',
        'OFFNAME' => 'string',
        'CADNUM' => 'string',
        'SHORTNAME' => 'string',
        'AOLEVEL' => 'int',
        'REGIONCODE' => ['string', 2],
        'AREACODE' => ['string', 3],
        'AUTOCODE' => ['string', 1],
        'CITYCODE' => ['string', 3],
        'CTARCODE' => ['string', 3],
        'PLACECODE' => ['string', 4],
        'PLANCODE' => ['string', 4],
        'STREETCODE' => ['string', 4],
        'EXTRCODE' => ['string', 4],
        'SEXTCODE' => ['string', 3],
        'PLAINCODE' => ['string', 15],
        'CURRSTATUS' => 'int',
        'ACTSTATUS' => 'int',
        'LIVESTATUS' => 'int',
        'CENTSTATUS' => 'int',
        'OPERSTATUS' => 'int',
        'IFNSFL' => ['string', 4],
        'IFNSUL' => ['string', 4],
        'TERRIFNSFL' => ['string', 4],
        'TERRIFNSUL' => ['string', 4],
        'OKATO' => ['string', 11],
        'OKTMO' => ['string', 11],
        'POSTALCODE' => ['string', 6],
        'STARTDATE' => 'date',
        'ENDDATE' => 'date',
        'UPDATEDATE' => 'date',
        'DIVTYPE' => 'int',
    ];

    /**
     * @var string
     */
    protected $indexName = 'address_objects';

    /**
     * @var string
     */
    protected $typeName = 'object';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'AOID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'AddressObjects';

    /**
     * @var string
     */
    protected $xmlPathElement = 'Object';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ADDROBJ_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ADDROBJ_*.XML';
}
