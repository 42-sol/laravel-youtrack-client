<?php

namespace YouTrackClient\Types;

use YouTrackClient\Core\YouTrackDataType;

class Issue extends YouTrackDataType
{
    protected string $idField = 'idReadable';

    protected function transform($data): array{
        $data['custom'] = [];

        foreach ($data['customFields'] as $customField) {
            $name = $customField['name'];

            $data['custom'][$name] = $customField;
        }

        unset($data['customFields']);

        return $data;
    }
}
