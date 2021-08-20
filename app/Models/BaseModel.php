<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Support\Str;

class BaseModel
{
    use HasAttributes;

    private bool $timestamps = false;
    private bool $incrementing = false;
    protected array $relations = [];

    /**
     * @return string|void
     */
    public function __call(string $name, array $arguments)
    {
        $startsWith = substr($name, 0, 3);
        $methodName = Str::snake(substr($name, 3));

        $match = match ($startsWith) {
            'set' => $this->setAttribute($methodName, $arguments[0]),
            'get' => $this->getAttribute($methodName)
        };

        if (!is_object($match)) {
            return $match;
        }
    }

    public function setId(string $url): void
    {
        $this->setAttribute('id', basename($url));
    }

    protected function relationLoad($entityRepository, $id): bool
    {
        $this->relations = $entityRepository->find($id)->getAttributes();

        if (static::create($this->relations)) {
            return true;
        }

        return false;
    }

    protected function getHasOne($classModel, $classRepository, array $relatedData, bool $simple): object|array|null
    {
        if ($simple) {
            return $this->getSimpleData($classModel, $relatedData);
        }

        return $this->getFullData($classModel, $classRepository, $relatedData);
    }

    protected function getHasMany($classModel, $classRepository, array $relatedData, bool $simple): ?array
    {
        if ($simple) {
            return $this->getSimpleDataList($classModel, $relatedData);
        }

        return $this->getFullDataList($classModel, $classRepository, $relatedData);
    }

    protected function getParsedData($data): array
    {
        if (is_array($data)) {
            return $data;
        }

        $urlPath = parse_url($data, PHP_URL_PATH);
        $explodeUrlPath = explode('/', trim($urlPath, '/'));

        return [
            'type' => $explodeUrlPath[1],
            'id' => $explodeUrlPath[2]
        ];
    }

    protected function getParsedDataList($dataList): array
    {
        $parsedList = [];

        foreach ($dataList as $data) {
            $parsedList[] = $this->getParsedData($data);
        }

        return $parsedList;
    }

    protected function usesTimestamps(): bool
    {
        return $this->timestamps;
    }

    protected function getIncrementing(): bool
    {
        return $this->incrementing;
    }

    private function getSimpleData($classModel, $relatedData)
    {
        $object = new $classModel();
        $object->setId($relatedData['id']);

        return $object;
    }

    private function getFullData($classModel, $classRepository, $relatedData)
    {
        $repository = new $classRepository();
        $attributes = $repository->find($relatedData['id'])->getAttributes();

        if ($relation = $classModel::create($attributes)) {
            return $relation;
        }

        return null;
    }

    private function getSimpleDataList($classModel, $relatedData): ?array
    {
        $relations = null;

        foreach ($relatedData as $item) {
            $object = new $classModel();
            $object->setId($item['id']);
            $relations[] = $object;
        }

        return $relations;
    }

    private function getFullDataList($classModel, $classRepository, $relatedData): ?array
    {
        $relations = null;

        foreach ($relatedData as $item) {
            $repository = new $classRepository();
            $attributes = $repository->find($item['id'])->getAttributes();
            $relations[] = $classModel::create($attributes);
        }

        return $relations;
    }
}
