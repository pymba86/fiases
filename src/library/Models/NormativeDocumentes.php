<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Нормативные документы.
 * @package Library\Models
 */
class NormativeDocumentes extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'NORMDOCID' => 'uuid',
        'DOCNAME' => ['string', 10000],
        'DOCDATE' => 'date',
        'DOCNUM' => 'string',
        'DOCTYPE' => 'string',
    ];

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'NORMDOCID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'NormativeDocumentes';

    /**
     * @var string
     */
    protected $xmlPathElement = 'NormativeDocument';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_NORMDOC_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_NORMDOC_*.XML';
}