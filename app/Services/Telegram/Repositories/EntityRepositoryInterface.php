<?php

namespace App\Services\Telegram\Repositories;

interface EntityRepositoryInterface
{
    public function getText();

    public function getInlineKeyboard();
}