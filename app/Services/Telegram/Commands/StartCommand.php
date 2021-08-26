<?php

namespace App\Services\Telegram\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start', '/s'];
    protected static $description = 'Send "/start" or "/s" to get "Hello, World!"';

    public function handle()
    {
        $this->sendMessage([
            'text' => $this->greeting()
        ]);
    }

    private function greeting(): string
    {
        return "Hello, {$this->update->user()->first_name}!" . PHP_EOL . PHP_EOL
        . 'Welcome to the world of Star Wars!' . PHP_EOL . PHP_EOL
        . 'Choose one of the following commands: /people, /planets, /films';
    }
}