<?php

namespace YouTrackClient\Types;

use Illuminate\Support\Arr;
use YouTrackClient\Core\YouTrackDataType;

class Issue extends YouTrackDataType
{
    protected string $idField = 'idReadable';

    protected function transform($data): array{
        $data['custom'] = [];

        if (Arr::has($data, 'customFields')) {
            foreach ($data['customFields'] as $customField) {
                $name = $customField['name'];

                $data['custom'][$name] = $customField;
            }

            unset($data['customFields']);
        }

        return $data;
    }
}
