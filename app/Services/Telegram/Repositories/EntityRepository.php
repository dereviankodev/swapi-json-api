<?php

namespace App\Services\Telegram\Repositories;

class EntityRepository extends BaseEntityRepository
{
    public function __construct(string $alias)
    {
        parent::__construct($alias);
    }

    public function getText(): string
    {
        return match ($this->currentDataType) {
            static::DATA_TYPE_INDEX => $this->getIndexText(),
            static::DATA_TYPE_READ => $this->getReadText(),
            static::DATA_TYPE_RELATED => $this->getRelatedText(),
            default => ''
        };
    }

    public function getInlineKeyboard(): array
    {
        return match ($this->currentDataType) {
            static::DATA_TYPE_INDEX => $this->getIndexInlineKeyboard(),
            static::DATA_TYPE_READ => $this->getReadInlineKeyboard(),
            static::DATA_TYPE_RELATED => $this->getRelatedKeyboard(),
            default => []
        };
    }
}