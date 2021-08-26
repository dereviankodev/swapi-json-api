<?php

namespace App\Services\Telegram\Commands;

use Illuminate\Support\Facades\Http;
use WeStacks\TeleBot\Handlers\CommandHandler;

class PeopleCommand extends CommandHandler
{
    protected static $aliases = ['/people'];
    protected static $description = 'Send "/people" to get all the people of the Star Wars universe';

    public function handle()
    {
        $this->sendMessage([
            'text' => $this->getPeopleResponse()
        ]);
    }

    private function getPeople(): string
    {
        return Http::send('GET', 'http://swapi-json-api.loc/api/v1/people')->body();
    }

    private function getPeopleResponse(): string
    {
        $response = $this->getPeople();
        $data = json_decode($response, true);

        $people = "";

        foreach ($data['data'] as $person) {
            $people .= 'id: ' . $person['id'] . PHP_EOL
                . 'name: ' . $person['attributes']['name'] . PHP_EOL . PHP_EOL;
        }

        return $people;
    }
}