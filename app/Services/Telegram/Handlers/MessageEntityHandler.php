<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Telegram\Repositories\EntityRepository;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class MessageEntityHandler extends UpdateHandler
{
    private const ENTITY_NAME = [
        '👨‍🚀 People' => 'people',
        '🎬 Films' => 'films',
        '🪐 Planets' => 'planets',
        '🐼 Species' => 'species',
        '🚀 Starships' => 'starships',
        '🛺 Vehicles' => 'vehicles'
    ];

    public static function trigger(Update $update, TeleBot $bot): bool
    {
        if (!isset($update->message)) {
            return false;
        }

        $entityNames = collect(static::ENTITY_NAME);

        return $entityNames->has($update->message->text);
    }

    public function handle()
    {
        $entity = new EntityRepository(static::ENTITY_NAME[$this->update->message->text]);
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