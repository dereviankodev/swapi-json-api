<?php

namespace App\Services\Telegram\Handlers;

use Illuminate\Support\Str;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class ByeHandler extends UpdateHandler
{

    public static function trigger(Update $update, TeleBot $bot): bool
    {
        if (!isset($update->message) || !isset($update->message->text)) {
            return false;
        }

        return Str::contains($update->message->text, [
            'bye', 'Bye', 'Have a', 'have a', 'good day', 'good one', 'care', 'later', 'Farewell', 'farewell',
            'right then', 'it easy', 'out', 'Godspeed', 'godspeed', 'Take it', 'take it', 'Peace', 'peace', 'I\'m out',
            'i\'m out', 'gotta', 'Toodle', 'toodle'
        ]);
    }

    public function handle()
    {
        $this->sendPhoto([
            'photo' => fopen(storage_path('app/public/telegram/images/bye.jpg'), 'r')
        ]);
    }
}