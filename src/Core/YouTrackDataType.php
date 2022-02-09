<?php

namespace YouTrackClient\Core;

use Illuminate\Support\Str;
use JsonSerializable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use YouTrackClient\YouTrackClient;

function transformField($field)
{
    $type = Arr::get($field, '$type');

    if ($type) {
        $camelType = Str::camel($type);
        $class = config("youtrack.mappings.$camelType.class");
        if ($class) {
            return new $class($field);
        }
    }

    return transformData($field);
}

function transformData($data)
{
    foreach ($data as $key => $field) {
        if (is_array($field)) {
            if (Arr::isAssoc($field)) {
                $data[$key] = transformField($field);
            } elseif (Arr::isList($field)) {
                $data[$key] = transformData($field);
            }
        }
    }

    return $data;
}

class YouTrackDataType implements JsonSerializable
{
    protected array $fields;
    protected array $removeFields = ['$type'];
    protected string $idField = 'id';
    protected YouTrackClient $client;

    public function __construct(array $data)
    {
        $this->fields = $this->transformFields($data);
        $this->client = App::make(YouTrackClient::class);
    }
    
    public function __get($property) {
        return Arr::get($this->fields, $property);
    }

    public function jsonSerialize()
    {
        return $this->fields;
    }

    protected function transform(array $data): array
    {
        return $data;
    }

    private function transformFields(array $data): array
    {
        $data['$id'] = Arr::get($data, 'id');
        $data['id'] = Arr::get($data, $this->idField);

        $transformedData = $this->transform(transformData($data));

        return Arr::except($transformedData, $this->removeFields);
    }
}
