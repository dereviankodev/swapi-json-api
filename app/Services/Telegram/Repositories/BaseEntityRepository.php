<?php

namespace App\Services\Telegram\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use function CloudCreativity\LaravelJsonApi\json_decode as api_json_decode;

abstract class BaseEntityRepository implements EntityRepositoryInterface
{
    protected const DATA_TYPE_INDEX = 'index';
    protected const DATA_TYPE_READ = 'read';
    protected const DATA_TYPE_RELATIONSHIP = 'relationship';

    protected string $currentDataType;
    private string $currentFullUri;
    private array $currentEntityData;
    private array $currentEntityMeta;
    private string $resourceType;
    private ?string $resourceId;
    private ?string $resourceRelationship;
    private Collection $urlPath;
    private array $urlQuery = [];

    public function __construct(
        private string $alias
    ) {
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

    protected function getReadText(): string
    {
        $type = ucfirst($this->currentEntityData['data']['type']);
        $id = $this->currentEntityData['data']['id'];

        $text = '<strong>Detail of '.Str::of($this->resourceType)->plural().':</strong>'.PHP_EOL
            ."<i>Type: $type,"
            ." ID: $id</i>"
            .PHP_EOL.PHP_EOL;

        foreach ($this->currentEntityData['data']['attributes'] as $attribute => $value) {
            if (is_array($value)) {
                continue;
            }

            if ($date = strtotime($value)) {
                $value = date('Y-m-d', $date);
            }

            $attributeFormatted = Str::of($attribute)
                ->ucfirst()
                ->replace('_', ' ')
                ->start('<strong>')
                ->finish(':</strong>'.PHP_EOL);
            $valueFormatted = Str::of($value)
                ->start('<em>')
                ->finish('</em>'.PHP_EOL.PHP_EOL);

            $text .= $attributeFormatted.$valueFormatted;
        }

        return $text;
    }

    protected function getRelationshipText(): string
    {
        $callbackName = $this->urlQuery['cb'];
        $relatedName = ucfirst($this->resourceRelationship);
        $text = "<strong>$relatedName associated with $callbackName</strong>".PHP_EOL;

        if (is_null($this->currentEntityData['data'])) {
            $text .= "<strong><em>No associated $relatedName</em></strong>";

            return $text;
        }

        $typeRelation = 'has one';
        $countRelatedEntity = 1;

        if (!Arr::isAssoc($this->currentEntityData['data'])) {
            $typeRelation = 'has many';
            $countRelatedEntity = count($this->currentEntityData['data']);
        }

        $text .= "<em>Relation type: $typeRelation, Relation count: $countRelatedEntity</em>";

        return $text;
    }

    protected function getIndexInlineKeyboard(): array
    {
        $entityData = $this->currentEntityData;
        $inlineKeyboard = [];

        foreach ($entityData['data'] as $item) {
            $attributes = $item['attributes'];
            $text = $attributes['name'] ?? $attributes['title'];
            $callback_data = $item['type'].'/'.$item['id'];

            if (isset($this->urlQuery['page']['number'])) {
                $callback_data .= '?page[number]='.$this->urlQuery['page']['number'];
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

    protected function getReadInlineKeyboard(): array
    {
        $attributes = $this->currentEntityData['data']['attributes'];
        $entityRelationships = $this->currentEntityData['data']['relationships'] ?? [];
        $inlineKeyboard = [];
        $pageNumber = $this->urlQuery['page']['number'] ?? 1;
        $callbackName = $attributes['name'] ?? $attributes['title'];

        foreach ($entityRelationships as $relationship => $item) {
            $text = ucfirst($relationship).' related';
            $callback_data = '/'.$this->currentEntityData['data']['type']
                .'/'.$this->currentEntityData['data']['id']
                .'/'.$relationship
                .'?page[number]='.$pageNumber
                .'&cb='.$callbackName;

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

        $comeback = [
            [
                [
                    'text' => '❎  Back to list of '.$this->resourceType,
                    'callback_data' => '/'.$this->resourceType.'/?page[number]='.$pageNumber
                ]
            ]
        ];

        return array_merge($inlineKeyboard, $comeback);
    }

    protected function getRelationshipKeyboard(): array
    {
        $inlineKeyboard = [];

        if (!is_null($this->currentEntityData['data'])) {
            if (Arr::isAssoc($this->currentEntityData['data'])) {
                $attributes = $this->currentEntityData['data']['attributes'];
                $text = $attributes['name'] ?? $attributes['title'];
                $callback_data = '/'.$this->currentEntityData['data']['type']
                    .'/'.$this->currentEntityData['data']['id']
                    .'?page[number]=1';

                $items = [
                    [
                        [
                            'text' => $text,
                            'callback_data' => $callback_data
                        ]
                    ]
                ];

                $inlineKeyboard = array_merge($inlineKeyboard, $items);
            } else {
                foreach ($this->currentEntityData['data'] as $item) {
                    $attributes = $item['attributes'];
                    $text = $attributes['name'] ?? $attributes['title'];
                    $callback_data = '/'.$item['type']
                        .'/'.$item['id']
                        .'?page[number]=1';

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
            }
        }

        $comeback = [
            [
                [
                    'text' => '❎  Back to '.$this->urlQuery['cb'],
                    'callback_data' => '/'.$this->resourceType
                        .'/'.$this->resourceId
                        .'/?page[number]='.$this->urlQuery['page']['number']
                ]
            ]
        ];

        return array_merge($inlineKeyboard, $comeback);
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
                $links = [
                    [
                        'text' => ucfirst($key),
                        'callback_data' => $parsedUrl['path'].'/?page[number]='.$query['page']['number']
                    ]
                ];

                $inlineKeyboard = array_merge($inlineKeyboard, $links);
            }
        }

        return $inlineKeyboard;
    }

    private function parseAlias()
    {
        $parseAlias = parse_url($this->alias);
        $this->urlPath = Str::of($parseAlias['path'])
            ->trim('/')
            ->explode('/');

        $this->resourceType = $this->urlPath->get(0);
        $this->resourceId = $this->urlPath->get(1);
        $this->resourceRelationship = $this->urlPath->get(2);

        if (isset($parseAlias['query'])) {
            parse_str($parseAlias['query'], $this->urlQuery);
        }
    }

    private function parseDataType()
    {
        $this->currentDataType = match ($this->urlPath->count()) {
            2 => static::DATA_TYPE_READ,
            3 => static::DATA_TYPE_RELATIONSHIP,
            default => static::DATA_TYPE_INDEX
        };
    }

    private function parseFullUri()
    {
        if ($this->currentDataType === static::DATA_TYPE_INDEX) {
            $this->currentFullUri = json_api()->url()->index($this->resourceType, $this->urlQuery);
        } elseif ($this->currentDataType === static::DATA_TYPE_READ) {
            $this->currentFullUri = json_api()->url()->read($this->resourceType, $this->resourceId, $this->urlQuery);
        } elseif ($this->currentDataType === static::DATA_TYPE_RELATIONSHIP) {
            $this->currentFullUri = json_api()->url()->relatedResource(
                $this->resourceType,
                $this->resourceId,
                $this->resourceRelationship,
                $this->urlQuery
            );
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

            return;
        }

        $meta = $this->currentEntityData['meta']['page'];

        $this->currentEntityMeta = [
            'total' => $meta['total'] ?? 'Unknown',
            'current_page' => $meta['current-page'] ?? 'Unknown',
            'total_page' => $meta['last-page'] ?? 'Unknown'
        ];
    }
}