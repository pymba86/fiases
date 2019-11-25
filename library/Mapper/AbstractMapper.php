<?php

namespace Library\Mapper;

/**
 * Базовый класс для универсального маппера
 * @package Library\Mapper
 */
abstract class AbstractMapper implements SqlMapperInterface, XmlMapperInterface
{
    use XmlMapperTrait, SqlMapperTrait {
        SqlMapperTrait::getMap insteadof XmlMapperTrait;
        SqlMapperTrait::mapArray insteadof XmlMapperTrait;
        SqlMapperTrait::convertToStrings insteadof XmlMapperTrait;
        SqlMapperTrait::convertToData insteadof XmlMapperTrait;
        SqlMapperTrait::initializeField insteadof XmlMapperTrait;
    }
}