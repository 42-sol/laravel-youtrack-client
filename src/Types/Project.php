<?php

namespace YouTrackClient\Types;

use YouTrackClient\Core\YouTrackDataType;

class Project extends YouTrackDataType
{
    protected string $idField = 'shortName';
}
