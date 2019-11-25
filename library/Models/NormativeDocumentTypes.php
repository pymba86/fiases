<?php

namespace Library\Models;

use Library\Mapper\AbstractMapper;

/**
 * Типы нормативных документов.
 * @package Library\Models
 */
class NormativeDocumentTypes extends AbstractMapper
{

    /**
     * @var mixed[]
     */
    protected $fields = [
        'NDTYPEID' => 'int',
        'NAME' => 'string',
    ];

    /**
     * @var string
     */
    protected $indexName = 'normative_document_types';

    /**
     * @var string
     */
    protected $typeName = 'normative_document_type';

    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'NDTYPEID';

    /**
     * @var string
     */
    protected $xmlPathRoot = 'NormativeDocumentTypes';

    /**
     * @var string
     */
    protected $xmlPathElement = 'NormativeDocumentType';

    /**
     * @var string
     */
    protected $insertFileMask = 'AS_NDOCTYPE_*.XML';

    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_NDOCTYPE_*.XML';
}