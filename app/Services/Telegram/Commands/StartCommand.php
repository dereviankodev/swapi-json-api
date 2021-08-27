<?php

namespace App\Services\Telegram\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start'];
    protected static $description = 'Send "/start" to get Star Wars data';

    public function handle()
    {
//        $resources = array_keys(config('json-api-v1.resources'));
//        array_unshift($resources, static::$aliases);
//        $this->bot->setMyCommands([
//            'commands' => $resources
//        ]);

        $this->sendMessage([
            'text' => $this->greeting(),
//            'reply_markup' => [
//                'inline_keyboard' => [
//                    [
//                        [
//                            'text' => 'People',
//                            'callback_data' => '/people'
//                        ]
//                    ],
//                    [
//                        [
//                            'text' => 'Planets',
//                            'callback_data' => '/planets'
//                        ]
//                    ],
//                    [
//                        [
//                            'text' => 'Films',
//                            'callback_data' => '/planets'
//                        ]
//                    ],
//                    [
//                        [
//                            'text' => 'Species',
//                            'callback_data' => '/species'
//                        ]
//                    ],
//                    [
//                        [
//                            'text' => 'Starships',
//                            'callback_data' => '/starships'
//                        ]
//                    ],
//                    [
//                        [
//                            'text' => 'Vehicles',
//                            'callback_data' => '/vehicles'
//                        ]
//                    ],
//                ]
//            ]
        ]);
    }

    private function greeting(): string
    {
        return "Hello, {$this->update->user()->first_name}!".PHP_EOL.PHP_EOL
            .'Welcome to the world of Star Wars!'.PHP_EOL.PHP_EOL
            .'Select one of the following commands to get a list';
    }
}