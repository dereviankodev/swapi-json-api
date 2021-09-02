<?php

namespace App\Services\Telegram\Commands;

use App\Services\Telegram\Repositories\EntityRepository;
use WeStacks\TeleBot\Handlers\CommandHandler;

class BaseCommand extends CommandHandler
{
    public function handle()
    {
        $entity = new EntityRepository(head(static::$aliases));

        $this->sendMessage([
            'text' => $entity->getText(),
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'inline_keyboard' => $entity->getInlineKeyboard()
            ]
        ]);
    }
}