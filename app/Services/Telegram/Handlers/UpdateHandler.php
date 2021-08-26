<?php

namespace App\Services\Telegram\Handlers;

use WeStacks\TeleBot\Interfaces\UpdateHandler as BaseUpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class UpdateHandler extends BaseUpdateHandler
{

    /**
     * @inheritDoc
     */
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        return isset($update->message);
    }

    /**
     * @inheritDoc
     */
    public function handle()
    {
        $update = $this->update;
        $bot = $this->bot;

        $this->sendMessage([
            'text' => 'Welcome from Handler'
        ]);
    }
}