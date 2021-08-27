<?php

namespace App\Services\Telegram\Handlers;

use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class CommandsHandler extends UpdateHandler
{
    /**
     * @inheritDoc
     */
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        var_dump($update->callback_query->data ?? null);
//        return isset($update->callback_query);
        return false;
    }

    /**
     * @inheritDoc
     */
    public function handle()
    {
        $update = $this->update;
        $bot = $this->bot;
        $callbackQueryId = $update->callback_query->id;

        $this->deleteMyCommands([
            'commands' => [
                [
                    'command' => 'people',
                    'description' => 'Get list all People'
                ]
            ]
        ]);
    }
}