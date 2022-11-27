<?php

rex_yform_manager_dataset::setModelClass(
    'rex_neues_entry',
    neues_entry::class
);
rex_yform_manager_dataset::setModelClass(
    'rex_neues_category',
    neues_category::class
);

if (rex_plugin::get('yform', 'rest')->isAvailable() && !rex::isSafeMode()) {
    /* YForm Rest API */
    $rex_neues_entry_route = new \rex_yform_rest_route(
        [
            'path' => '/v0.dev/neues/date/',
            'auth' => '\rex_yform_rest_auth_token::checkToken',
            'type' => \neues_entry::class,
            'query' => \neues_entry::query(),
            'get' => [
                'fields' => [
                    'rex_neues_entry' => [
                        'id',
                        'name',
                        'description',
                        'images',
                        'status'
                    ],
                    'rex_neues_category' => [
                        'id',
                        'name'
                    ],
                ],
            ],
            'post' => [
                'fields' => [
                    'rex_neues_entry' => [
                        'name',
                        'description',
                        'images'
                    ],
                ],
            ],
            'delete' => [
                'fields' => [
                    'rex_neues_entry' => [
                        'id',
                    ],
                ],
            ],
        ]
    );

    \rex_yform_rest::addRoute($rex_neues_entry_route);

    /* YForm Rest API */
    $rex_neues_category_route = new \rex_yform_rest_route(
        [
            'path' => '/v0.dev/neues/category/',
            'auth' => '\rex_yform_rest_auth_token::checkToken',
            'type' => \neues_category::class,
            'query' => \neues_category::query(),
            'get' => [
                'fields' => [
                    'rex_neues_category' => [
                        'id',
                        'name'
                    ],
                ],
            ],
            'post' => [
                'fields' => [
                    'rex_neues_category' => [
                        'name'
                    ],
                ],
            ],
            'delete' => [
                'fields' => [
                    'rex_neues_category' => [
                        'id',
                    ],
                ],
            ],
        ]
    );

    \rex_yform_rest::addRoute($rex_neues_category_route);
}
