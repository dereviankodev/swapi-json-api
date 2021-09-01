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
        $text = '';

        if ($this->currentDataType === static::DATA_TYPE_INDEX) {
            $text = $this->getIndexText();
        } elseif ($this->currentDataType === static::DATA_TYPE_READ) {
            $text = $this->getReadText();
        } elseif ($this->currentDataType === static::DATA_TYPE_RELATIONSHIP) {
            $text = $this->getRelationshipText();
        }

        return $text;
    }

    public function getInlineKeyboard()
    {
        $inlineKeyboard = [];

        if ($this->currentDataType === static::DATA_TYPE_INDEX) {
            $inlineKeyboard = $this->getIndexInlineKeyboard();
        } elseif ($this->currentDataType === static::DATA_TYPE_READ) {
            $inlineKeyboard = $this->getReadInlineKeyboard();
        } elseif ($this->currentDataType === static::DATA_TYPE_RELATIONSHIP) {
            $inlineKeyboard = $this->getRelationshipKeyboard();
        }

        return $inlineKeyboard;
    }
}