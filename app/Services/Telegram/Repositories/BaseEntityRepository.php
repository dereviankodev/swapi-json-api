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
    protected const DATA_TYPE_RELATED = 'related';

    protected string $currentDataType;
    private string $currentFullUri;
    private array $currentEntityData;
    private array $currentEntityMeta;
    private string $resourceType;
    private ?string $resourceId;
    private ?string $resourceRelated;
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
        $text = __('telebot.repository.index.text', ['resource_type' => Str::of($this->resourceType)->plural()]);

        if (!empty($this->currentEntityMeta)) {
            $text .= __('telebot.repository.index.meta', [
                'total' => $this->currentEntityMeta['total'],
                'current_page' => $this->currentEntityMeta['current_page'],
                'total_page' => $this->currentEntityMeta['total_page']
            ]);
        }

        return $text;
    }

    protected function getReadText(): string
    {
        $type = ucfirst($this->currentEntityData['data']['type']);
        $id = $this->currentEntityData['data']['id'];

        $text = __('telebot.repository.read.text', [
            'resource_type' => Str::of($this->resourceType)->plural(),
            'type' => $type,
            'id' => $id
        ]);

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

    protected function getRelatedText(): string
    {
        $callbackName = $this->urlQuery['cb'];
        $relatedName = ucfirst($this->resourceRelated);
        $text = __('telebot.repository.related.text.associated', [
            'related_name' => $relatedName,
            'callback_name' => $callbackName
        ]);

        if (is_null($this->currentEntityData['data'])) {
            $text .= __('telebot.repository.related.text.no_associated', ['related_name' => $relatedName]);

            return $text;
        }

        $typeRelation = __('telebot.repository.related.type.has_one');
        $countRelatedEntity = 1;

        if (!Arr::isAssoc($this->currentEntityData['data'])) {
            $typeRelation = __('telebot.repository.related.type.has_many');
            $countRelatedEntity = count($this->currentEntityData['data']);
        }

        $text .= __('telebot.repository.related.meta', [
            'type' => $typeRelation,
            'count' => $countRelatedEntity
        ]);

        return $text;
    }

    protected function getIndexInlineKeyboard(): array
    {
        $entityData = $this->currentEntityData;
        $inlineKeyboard = [];

        foreach ($entityData['data'] as $item) {
            $attributes = $item['attributes'];
            $text = $attributes['name'] ?? $attributes['title'];
            $callback_data = __('telebot.repository.index.inline_keyboard.callback.path', [
                'type' => $item['type'],
                'id' => $item['id']
            ]);

            if (isset($this->urlQuery['page']['number'])) {
                $callback_data .= __('telebot.repository.index.inline_keyboard.callback.query', [
                    'number' => $this->urlQuery['page']['number']
                ]);
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
            $text = __('telebot.repository.read.inline_keyboard.callback.text', ['relationship' => $relationship]);
            $callback_data = __('telebot.repository.read.inline_keyboard.callback.path', [
                'type' => $this->currentEntityData['data']['type'],
                'id' => $this->currentEntityData['data']['id'],
                'related' => $relationship,
                'number' => $pageNumber,
                'callback_name' => $callbackName
            ]);

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
                    'text' => __('telebot.repository.read.inline_keyboard.comeback.text', [
                        'resource_type' => $this->resourceType
                    ]),
                    'callback_data' => __('telebot.repository.read.inline_keyboard.comeback.data', [
                        'resource_type' => $this->resourceType,
                        'number' => $pageNumber
                    ])
                ]
            ]
        ];

        return array_merge($inlineKeyboard, $comeback);
    }

    protected function getRelatedKeyboard(): array
    {
        $inlineKeyboard = [];

        if (!is_null($this->currentEntityData['data'])) {
            if (Arr::isAssoc($this->currentEntityData['data'])) {
                $attributes = $this->currentEntityData['data']['attributes'];
                $text = $attributes['name'] ?? $attributes['title'];
                $callback_data = __('telebot.repository.related.inline_keyboard.callback.data', [
                    'type' => $this->currentEntityData['data']['type'],
                    'id' => $this->currentEntityData['data']['id']
                ]);

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
                    $callback_data = __('telebot.repository.related.inline_keyboard.callback.data', [
                        'type' => $item['type'],
                        'id' => $item['id']
                    ]);

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
                    'text' => __('telebot.repository.related.inline_keyboard.comeback.text', [
                        'cb' => $this->urlQuery['cb']
                    ]),
                    'callback_data' => __('telebot.repository.related.inline_keyboard.comeback.data', [
                        'type' => $this->resourceType,
                        'id' => $this->resourceId,
                        'number' => $this->urlQuery['page']['number']
                    ])
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
                        'callback_data' => __('telebot.repository.pagination.inline_keyboard.callback.data', [
                            'path' => $parsedUrl['path'],
                            'number' => $query['page']['number']
                        ])
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
        $this->resourceRelated = $this->urlPath->get(2);

        if (isset($parseAlias['query'])) {
            parse_str($parseAlias['query'], $this->urlQuery);
        }
    }

    private function parseDataType()
    {
        $this->currentDataType = match ($this->urlPath->count()) {
            2 => static::DATA_TYPE_READ,
            3 => static::DATA_TYPE_RELATED,
            default => static::DATA_TYPE_INDEX
        };
    }

    private function parseFullUri()
    {
        if ($this->currentDataType === static::DATA_TYPE_INDEX) {
            $this->currentFullUri = json_api()->url()->index($this->resourceType, $this->urlQuery);
        } elseif ($this->currentDataType === static::DATA_TYPE_READ) {
            $this->currentFullUri = json_api()->url()->read($this->resourceType, $this->resourceId, $this->urlQuery);
        } elseif ($this->currentDataType === static::DATA_TYPE_RELATED) {
            $this->currentFullUri = json_api()->url()->relatedResource(
                $this->resourceType,
                $this->resourceId,
                $this->resourceRelated,
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
        $unknown = __('telebot.repository.meta.unknown');

        $this->currentEntityMeta = [
            'total' => $meta['total'] ?? $unknown,
            'current_page' => $meta['current-page'] ?? $unknown,
            'total_page' => $meta['last-page'] ?? $unknown
        ];
    }
}