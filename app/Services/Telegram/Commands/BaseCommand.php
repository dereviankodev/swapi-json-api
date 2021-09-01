<?php

namespace App\Services\Telegram\Commands;

use App\Services\Telegram\Repositories\EntityRepository;
use WeStacks\TeleBot\Handlers\CommandHandler;

class BaseCommand extends CommandHandler
{
    public function handle()
    {
        $entity = new EntityRepository(head(static::$aliases));
        $text = $entity->getText();
        $inlineKeyboard = $entity->getInlineKeyboard();

        $this->sendMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'inline_keyboard' => $inlineKeyboard
            ]
        ]);
    }
}