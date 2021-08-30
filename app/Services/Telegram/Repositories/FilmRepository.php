<?php

namespace App\Services\Telegram\Repositories;

use App\Services\Telegram\Helpers\CallbackToAction;
use App\Services\Telegram\Helpers\CallbackToUri;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Str;

use function CloudCreativity\LaravelJsonApi\json_decode as api_json_decode;

class FilmRepository
{
    private string $action;
    private string $uri;
    private array|object $data;

    public function __construct(
        private string $callbackQuery
    ) {
        $this->getData();
    }

    /**
     * @throws Exception
     */
    public function getRequest()
    {
        $actionName = 'prepare'.$this->action.'Request';

        if (method_exists(static::class, $actionName)) {
            return $this->{$actionName}();
        }

        throw new Exception('Unknown repository action');
    }

    public function getDescription($name): string
    {
        if ($this->action === 'Index') {
            return 'List of ' . Str::of($name)->plural() . ':';
        }

        return 'Complete information about:';
    }

    public function getMeta(): ?string
    {
        if ($this->action === 'Index') {
            $total = $this->data['meta']['page']['total'] ?? 'Unknown';
            $current_page = $this->data['meta']['page']['current-page'] ?? 'Unknown';
            $total_page = $this->data['meta']['page']['last-page'] ?? 'Unknown';

            return "<i>Total: $total, Current page: $current_page, Total page: $total_page</i>";
        }

        return null;
    }

    private function getResponse($uri): Response
    {
        return Http::send('GET', $uri);
    }

    private function prepareIndexRequest(): array
    {
        $requestKeyboard = [];

        foreach ($this->data['data'] as $item) {
            $items = [
                [
                    [
                        'text' => $item['attributes']['title'],
                        'callback_data' => $item['type'].'/'.$item['id']
                    ]
                ]
            ];

            $requestKeyboard = array_merge($requestKeyboard, $items);
        }

        if (isset($this->data['links'])) {
            $pagination = $this->preparePaginationRequest();
            $requestKeyboard = array_merge($requestKeyboard, [$pagination]);
        }

        return $requestKeyboard;
    }

    private function preparePaginationRequest(): array
    {
        $links = [];

        foreach ($this->data['links'] as $key => $link) {
            $url = urldecode($link);
            $parseUrl = parse_url(basename($url));
            $query = [];
            parse_str($parseUrl['query'], $query);
            $tmp = [
                [
                    'text' => ucfirst($key),
                    'callback_data' => $parseUrl['path'].'/?page[number]='.$query['page']['number']
                ]
            ];

            $links = array_merge($links, $tmp);
        }

        return $links;
    }

    private function getData()
    {
        $this->action = CallbackToAction::getAction($this->callbackQuery);
        $this->uri = CallbackToUri::getUri($this->callbackQuery);
        $this->data = api_json_decode($this->getResponse($this->uri), true);
    }
}