<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;

class BaseModel
{
    use HasAttributes;

    private bool $timestamps = false;
    private bool $incrementing = false;
    protected array $relations = [];

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
