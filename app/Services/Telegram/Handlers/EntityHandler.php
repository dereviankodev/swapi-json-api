<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Telegram\Repositories\EntityRepository;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\TeleBot;

class EntityHandler extends UpdateHandler
{
    private const ENTITY_NAME = [
        'people',
        'films',
        'planets',
        'species',
        'starships',
        'vehicles'
    ];

    public static function trigger(Update $update, TeleBot $bot): bool
    {
        if (!isset($update->callback_query)) {
            return false;
        }

        $entityNames = collect(static::ENTITY_NAME);
        $callbackCollection = Str::of($update->callback_query->data)->explode('/');
        $resourceType = $callbackCollection->first();

        return $entityNames->contains($resourceType);
    }

    public function handle()
    {
        $entity = new EntityRepository($this->update->callback_query->data);
        $text = $entity->getText();
        $inlineKeyboard = $entity->getInlineKeyboard();

        $this->deleteMessage();

        $this->sendMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'inline_keyboard' => $inlineKeyboard
            ]
        ]);
    }
}