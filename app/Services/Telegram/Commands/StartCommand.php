<?php

namespace App\Services\Telegram\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start'];
    protected static $description = 'Greetings';

    public function handle()
    {
        $this->sendMessage([
            'text' => $this->greeting(),
            'reply_markup' => [
                'keyboard' => [
                    [
                        ['text' => '👨‍🚀 People'],
                        ['text' => '🎬 Films'],
                    ],
                    [
                        ['text' => '🪐 Planets'],
                        ['text' => '🐼 Species'],
                    ],
                    [
                        ['text' => '🚀 Starships'],
                        ['text' => '🛺 Vehicles'],
                    ],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
                'input_field_placeholder' => 'Select the section you are interested in ...',
            ]
        ]);
    }

    private function greeting(): string
    {
        return "Hello, {$this->update->user()->first_name}!".PHP_EOL.PHP_EOL
            .'Welcome to the world of Star Wars!'.PHP_EOL.PHP_EOL
            .'Select one of the following commands to get a list';
    }
}