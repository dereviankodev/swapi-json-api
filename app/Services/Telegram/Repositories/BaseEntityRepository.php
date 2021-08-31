<?php

namespace App\Services\Telegram\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use function CloudCreativity\LaravelJsonApi\json_decode as api_json_decode;

abstract class BaseEntityRepository implements EntityRepositoryInterface
{
    protected const DATA_TYPE_INDEX = 'index';
    protected const DATA_TYPE_READ = 'read';

    protected string $currentDataType;
    private string $currentFullUri;
    private array $currentEntityData;
    private array $currentEntityMeta;

    private string $resourceType;
    private ?string $resourceId;
    private Collection $pathCollection;
    private array $query = [];

    public function __construct(
        private string $alias
    ) { // `/entity`, `/entity/\d+/`, `/entity/?page[number]=\d+`
        $this->parseAlias();
        $this->parseDataType();
        $this->parseFullUri();
        $this->parseData();
        $this->parseMeta();
    }

    abstract public function getText();

    abstract public function getInlineKeyboard();

    protected function getIndexText(): string
    {
        $text = 'List of '.Str::of($this->resourceType)->plural().':';

        if (!empty($this->currentEntityMeta)) {
            $text .= PHP_EOL
                ."<i>Total: {$this->currentEntityMeta['total']},"
                ." Current page: {$this->currentEntityMeta['current_page']},"
                ." Total page: {$this->currentEntityMeta['total_page']}</i>";
        }

        return $text;
    }

    protected function getReadText()
    {
    }

    protected function getIndexInlineKeyboard(): array
    {
        $entityData = $this->currentEntityData;
        $inlineKeyboard = [];

        foreach ($entityData['data'] as $item) {
            $attributes = $item['attributes'];
            $text = $attributes['name'] ?? $attributes['title'];
            $callback_data = $item['type'].'/'.$item['id'];
            if (isset($this->query['page']['number'])) {
                $callback_data .= '?page[number]=' . $this->query['page']['number'];
            }

            $items = [
                [
                    [
                        'text' => $text,
                        'callback_data' => $callback_data
                    ]
                ]
            ];

            $inlineKeyboard = array_merge($inlineKeyboard, $items);
        }

        if (isset($entityData['links'])) {
            $pagination = $this->getPagination();
            $inlineKeyboard = array_merge($inlineKeyboard, [$pagination]);
        }

        return $inlineKeyboard;
    }

    private function getPagination(): array
    {
        $entityLinks = $this->currentEntityData['links'];
        $inlineKeyboard = [];

        foreach ($entityLinks as $key => $link) {
            $uri = urldecode($link);
            $parsedUrl = parse_url(basename($uri));
            $query = [];
            parse_str($parsedUrl['query'], $query);
            if ($query['page']['number'] != $this->currentEntityMeta['current_page']) {
                $tmp = [
                    [
                        'text' => ucfirst($key),
                        'callback_data' => $parsedUrl['path'].'/?page[number]='.$query['page']['number']
                    ]
                ];

                $inlineKeyboard = array_merge($inlineKeyboard, $tmp);
            }
        }

        return $inlineKeyboard;
    }

    private function parseAlias()
    {
        $parseAlias = parse_url($this->alias);
        $this->pathCollection = Str::of($parseAlias['path'])
            ->trim('/')
            ->explode('/');

        $this->resourceType = $this->pathCollection->get(0);
        $this->resourceId = $this->pathCollection->get(1);

        if (isset($parseAlias['query'])) {
            parse_str($parseAlias['query'], $this->query);
        }
    }

    private function parseDataType()
    {
        $this->currentDataType = match ($this->pathCollection->count()) {
            1 => static::DATA_TYPE_INDEX,
            2 => static::DATA_TYPE_READ
        };
    }

    private function parseFullUri()
    {
        if ($this->currentDataType === static::DATA_TYPE_INDEX) {
            $this->currentFullUri = json_api()->url()->index($this->resourceType, $this->query);
        } elseif ($this->currentDataType === static::DATA_TYPE_READ) {
            $this->currentFullUri = json_api()->url()->read($this->resourceType, $this->resourceId, $this->query);
        }
    }

    private function parseData()
    {
        $response = Http::send('GET', $this->currentFullUri);
        $this->currentEntityData = api_json_decode($response, true);
    }

    private function parseMeta()
    {
        if (!isset($this->currentEntityData['meta']['page'])) {
            $this->currentEntityMeta = [];
        }

        $meta = $this->currentEntityData['meta']['page'];

        $this->currentEntityMeta = [
            'total' => $meta['total'] ?? 'Unknown',
            'current_page' => $meta['current-page'] ?? 'Unknown',
            'total_page' => $meta['last-page'] ?? 'Unknown'
        ];
    }
}