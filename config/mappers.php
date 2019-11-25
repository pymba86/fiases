<?php

use Library\Models\ActualStatuses;
use \Library\Models\AddressObjectTypes;
use Library\Models\CenterStatuses;
use Library\Models\CurrentStatuses;
use Library\Models\EstateStatuses;
use Library\Models\FlatTypes;
use Library\Models\HouseStateStatuses;
use Library\Models\IntervalStatuses;
use Library\Models\NormativeDocumentTypes;
use Library\Models\OperationStatuses;
use Library\Models\RoomTypes;
use Library\Models\StructureStatuses;
use Library\Models\AddressObjects;
use Library\Models\Steads;
use Library\Models\Houses;
use Library\Models\Rooms;

/**
 * Список мапперов, которые будут применены к архиву ФИАС
 * В случае если один из них не понадобиться, нужно закоментировать - он будет пропушен
 */
return [
    FlatTypes::class,
    HouseStateStatuses::class,
    IntervalStatuses::class,
    NormativeDocumentTypes::class,
    OperationStatuses::class,
    RoomTypes::class,
    StructureStatuses::class,
    AddressObjects::class,
    Steads::class,
    Rooms::class,
    Houses::class,
    ActualStatuses::class,
    CenterStatuses::class,
    CurrentStatuses::class,
    EstateStatuses::class,
    AddressObjectTypes::class
];
