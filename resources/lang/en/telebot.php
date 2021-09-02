<?php

return [
    'command' => [
        'start' => [
            'text' => "Hi, :name!".PHP_EOL.PHP_EOL
                .'Welcome to the world of Star Wars!'.PHP_EOL.PHP_EOL
                .'Select one of the following commands to get a list',
            'input_field_placeholder' => 'Select the section you are interested in ...',
            'keyboard' => [
                'people' => '👨‍🚀 People',
                'films' => '🎬 Films',
                'planets' => '🪐 Planets',
                'species' => '🐼 Species',
                'starships' => '🚀 Starships',
                'vehicles' => '🛺 Vehicles',
            ]
        ]
    ],
    'repository' => [
        'index' => [
            'text' => '<strong>List of :resource_type:</strong>',
            'meta' => PHP_EOL.'<i>Total: :total, Current page: :current_page, Total page: :total_page</i>',
            'inline_keyboard' => [
                'callback' => [
                    'path' => ':type/:id',
                    'query' => '?page[number]=:number',
                ]
            ]
        ],
        'read' => [
            'text' => '<strong>Detail of :resource_type:</strong>'.PHP_EOL.'<i>Type: :type, ID: :id</i>'.PHP_EOL.PHP_EOL,
            'inline_keyboard' => [
                'callback' => [
                    'text' => ':Relationship related',
                    'path' => '/:type/:id/:related?page[number]=:number&cb=:callback_name'
                ],
                'comeback' => [
                    'text' => '❎  Back to list of :resource_type',
                    'data' => '/:resource_type/?page[number]=:number'
                ]
            ]
        ],
        'related' => [
            'text' => [
                'associated' => '<strong>:related_name associated with :callback_name</strong>'.PHP_EOL,
                'no_associated' => '<strong><em>No associated :related_name</em></strong>'
            ],
            'meta' => '<em>Relation type: :type, Relation count: :count</em>',
            'type' => [
                'has_one' => 'has one',
                'has_many' => 'has many'
            ],
            'inline_keyboard' => [
                'callback' => [
                    'data' => '/:type/:id?page[number]=1'
                ],
                'comeback' => [
                    'text' => '❎  Back to :cb',
                    'data' => '/:type/:id/?page[number]=:number'
                ]
            ]
        ],
        'pagination' => [
            'inline_keyboard' => [
                'callback' => [
                    'data' => ':path/?page[number]=:number'
                ],
            ]
        ],
        'meta' => [
            'unknown' => 'Unknown'
        ]
    ]
];