<?php

return [
    'enabled' => env('REQUEST_DOCS_API_ENABLED',true),
    // change it to true will make lrd to throw exception if rules in request class need to be changed
    // keep it false
    'debug' => env('REQUEST_DOCS_API_ENABLED',false),

    /*
    * Route where request docs will be served from laravel app.
    * localhost:8080/request-docs
    */
    'url' => 'request-docs',
    'middlewares' => [
        // \Rakutentech\LaravelRequestDocs\NotFoundWhenProduction::class,
    ],

    //Use only routes where ->uri start with next string Using Str::startWith( . e.g. - /api/mobile
    'only_route_uri_start_with' => 'api',

    'hide_matching' => [
        '#^telescope#',
        '#^docs#',
        '#^request-docs#',
        '#^api-docs#',
        '#^sanctum#',
        '#^_ignition#',
        '#^_tt#',
    ],

    'hide_meta_data' => env('REQUEST_DOCS_API_ENABLED',true),
    'hide_sql_data' => env('REQUEST_DOCS_API_ENABLED',false),
    'hide_logs_data' => env('REQUEST_DOCS_API_ENABLED',false),
    'hide_models_data' => env('REQUEST_DOCS_API_ENABLED',false),

    // https://github.com/rakutentech/laravel-request-docs/pull/92
    // When rules are put in other method than rules()
    'rules_methods' => [
        'rules'
    ],
    // Can be overridden as // @LRDresponses 200|400|401
    'default_responses' => [ "200", "400", "401", "403", "404", "405", "422", "429", "500", "503"],

    // By default, LRD group your routes by the first /path.
    // This is a set of regex to group your routes by prefix.
    'group_by' => [
        'uri_patterns' => [
            '^api/v[\d]+/', // `/api/v1/users/store` group as `/api/v1/users`.
            '^api/',        // `/api/users/store` group as `/api/users`.
        ]
    ],

    // No need to touch below
    // open api config
    // used to generate open api json
    'open_api' => [
        'title' => 'Laravel Request Docs',
        'description' => 'Laravel Request Docs',
        // default version that this library provides
        'version' => '3.0.0',
        // changeable
        'document_version' => '1.0.0',
        // license that you want to display
        'license' => 'Apache 2.0',
        'license_url' => 'https://www.apache.org/licenses/LICENSE-2.0.html',
        'server_url' => env('APP_URL', 'http://localhost'),
        //openapi 3.0.x doesn't support request body for delete operation

        // for now putting default responses for all. This can be changed later based on specific needs
        'responses' => [
            '200' => [
                'description' => 'Successful operation',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '400' => [
                'description' => 'Bad Request',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '401' => [
                'description' => 'Unauthorized',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '403' => [
                'description' => 'Forbidden',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '404' => [
                'description' => 'Not Found',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '422' => [
                'description' => 'Unprocessable Entity',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '500' => [
                'description' => 'Internal Server Error',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            'default' => [
                'description' => 'Unexpected error',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
        ],
    ],

    //export request docs as json file from terminal
    //from project root directory
    'export_path' => 'api.json'
];
