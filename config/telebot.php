<?php

use App\Services\Telegram\{Commands\FilmCommand,
    Commands\PeopleCommand,
    Commands\PlanetCommand,
    Commands\SpeciesCommand,
    Commands\StarshipCommand,
    Commands\StartCommand,
    Commands\VehicleCommand,
    Handlers\CallbackEntityHandler,
    Handlers\MessageEntityHandler
};

return [
    /*-------------------------------------------------------------------------
    | Default Bot Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the bots you wish to use as
    | your default bot for regular use.
    |
    */

    'default' => 'SWAPI_bot',

    /*-------------------------------------------------------------------------
    | Your Telegram Bots
    |--------------------------------------------------------------------------
    | You may use multiple bots. Each bot that you own should be configured here.
    |
    | See the docs for parameters specification:
    | https://westacks.github.io/telebot/#/configuration
    |
    */

    'bots' => [
        'SWAPI_bot' => [
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'name' => env('TELEGRAM_BOT_NAME'),
            'api_url' => 'http://localhost:8081',
            'exceptions' => true,
            'async' => false,

            'webhook' => [],

            'poll' => [],

            'handlers' => [
                StartCommand::class,
                PeopleCommand::class,
                FilmCommand::class,
                PlanetCommand::class,
                SpeciesCommand::class,
                StarshipCommand::class,
                VehicleCommand::class,

                MessageEntityHandler::class,
                CallbackEntityHandler::class
            ],
        ],
    ],
];
