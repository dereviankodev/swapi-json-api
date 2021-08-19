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

        if (is_string($match) || is_array($match)) {
            return $match;
        }
    }

    protected function relationLoad($entityRepository, $id): bool
    {
        $this->relations = $entityRepository->find($id)->getAttributes();

        if (static::create($this->relations)) {
            return true;
        }

        return false;
    }

    public function setId(string $url): void
    {
        $this->setAttribute('id', basename($url));
    }

    /**
     * Determine if the model uses timestamps.
     */
    protected function usesTimestamps(): bool
    {
        return $this->timestamps;
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     */
    protected function getIncrementing(): bool
    {
        return $this->incrementing;
    }
}
