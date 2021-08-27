<?php

namespace App\Services\Telegram\Commands;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use WeStacks\TeleBot\Handlers\CommandHandler;

class PeopleCommand extends CommandHandler
{
    protected static $aliases = ['/people'];
    protected static $description = 'Send "/people" to get all the people of the Star Wars universe';

    public function handle()
    {
        $text = $this->getPeopleList();

//        var_export($text);die;

        $this->sendMessage([
            'text' => 'People list',
            'reply_markup' => [
                'inline_keyboard' => $text
            ]
        ]);
    }

    private function getPeople(): Response
    {
        $urlPeopleList = json_api()->url()->index('people');
        return Http::send('GET', $urlPeopleList);
    }

    private function getPeopleList(): array|string
    {
        $response = $this->getPeople();

//        if ($response->clientError()) {
//            return "Please try later \xF0\x9F\x98\x93";
//        }

        $data = json_decode($response, true);

        $people = [];

        foreach ($data['data'] as $person) {
            $temp = [
                [
                    [
                        'text' => $person['attributes']['name'],
                        'callback_data' => $person['type'].'/'.$person['id']
                    ]
                ]
            ];

            $people = array_merge($people, $temp);
        }

        $people = array_merge($people, [$this->getPagination($data['links'])]);

//        foreach ($data['data'] as $person) {
//            $people .= 'id: ' . $person['id'] . PHP_EOL
//                . 'name: ' . $person['attributes']['name'] . PHP_EOL . PHP_EOL;
//        }

//        var_export($people);
//        die;
        return $people;
    }

    private function getPagination(array $links): array
    {
        $arr = [];
        foreach ($links as $key => $link) {
            $url = urldecode($link);
            $parseUrl = parse_url(basename($url));
            $query = [];
            parse_str($parseUrl['query'], $query);
            $tmp = [
                [
                    'text' => ucfirst($key),
                    'callback_data' => $parseUrl['path'] . '/' . $query['page']['number']
                ]
            ];

            $arr = array_merge($arr, $tmp);
        }

        return $arr;
    }
}