<?php

namespace App\Services\Telegram\Repositories;

class EntityRepository extends BaseEntityRepository
{
    public function __construct(string $alias) // `/entity`, `/entity/\d+/`, `/entity/?page[number]=\d+`
    {
        parent::__construct($alias);
    }

    public function getText()
    {
        if ($this->currentDataType === static::DATA_TYPE_INDEX) {
            return $this->getIndexText();
        } elseif ($this->currentDataType === static::DATA_TYPE_READ) {
            return $this->getReadText();
        }
    }

    public function getInlineKeyboard()
    {
        if ($this->currentDataType === static::DATA_TYPE_INDEX) {
            return $this->getIndexInlineKeyboard();
        } elseif ($this->currentDataType === static::DATA_TYPE_READ) {
            return $this->getReadInlineKeyboard();
        }
    }
}