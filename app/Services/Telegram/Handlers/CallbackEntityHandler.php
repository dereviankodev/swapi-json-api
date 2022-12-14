<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Telegram\Repositories\EntityRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\TeleBot;

class CallbackEntityHandler extends UpdateHandler
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
        if (!isset($update->callback_query) || !isset($update->callback_query->data)) {
            return false;
        }

        $entityNames = collect(static::ENTITY_NAME);
        $callbackCollection = Str::of($update->callback_query->data)->trim('/')->explode('/');
        $resourceType = $callbackCollection->first();

        return $entityNames->contains($resourceType);
    }

    public function handle()
    {
        $entity = new EntityRepository($this->update->callback_query->data);
        $text = $entity->getText();
        $inlineKeyboard = $entity->getInlineKeyboard();

        try {
            $this->deleteMessage();
        } catch (Exception $e) {
            Log::error($e);
        }

        $this->sendMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'inline_keyboard' => $inlineKeyboard
            ]
        ]);
    }
}