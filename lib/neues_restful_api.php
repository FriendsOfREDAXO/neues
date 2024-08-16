<?php

namespace FriendsOfRedaxo\Neues;

use rex_yform_rest;
use rex_yform_rest_route;

class RestfulApi
{
    public static function init(): void
    {
        $rex_neues_entry_route = new rex_yform_rest_route(
            [
                'path' => '/neues/entry/5.0.0/',
                'auth' => '\rex_yform_rest_auth_token::checkToken',
                'type' => Entry::class,
                'query' => Entry::query(),
                'get' => [
                    'fields' => [
                        'FriendsOfRedaxo\Neues\Entry' => [
                            'id',
                            'status',
                            'name',
                            'teaser',
                            'description',
                            'domain_ids',
                            'lang_id',
                            'publishdate',
                            'author_id',
                            'category_ids',
                            'url',
                            'image',
                            'images',
                            'createdate',
                            'createuser',
                            'updatedate',
                            'updateuser',
                            'uuid',
                        ],
                        'FriendsOfRedaxo\Neues\Category' => [
                            'id',
                            'name',
                            'image',
                            'status',
                            'createdate',
                            'createuser',
                            'updatedate',
                            'updateuser',
                            'uuid',
                        ],
                        'FriendsOfRedaxo\Neues\Author' => [
                            'id',
                            'name',
                            'nickname',
                            'text',
                            'image',
                            'be_user_id',
                            'createdate',
                            'createuser',
                            'updatedate',
                            'updateuser',
                            'uuid',
                        ],
                    ],
                ],
                'post' => [
                    'fields' => [
                        'FriendsOfRedaxo\Neues\Entry' => [
                            'status',
                            'name',
                            'teaser',
                            'description',
                            'domain_ids',
                            'lang_id',
                            'publishdate',
                            'author_id',
                            'url',
                            'image',
                            'images',
                            'createdate',
                            'createuser',
                            'updatedate',
                            'updateuser',
                        ],
                    ],
                ],
                'delete' => [
                    'fields' => [
                        'FriendsOfRedaxo\Neues\Entry' => [
                            'id',
                        ],
                    ],
                ],
            ],
        );

        rex_yform_rest::addRoute($rex_neues_entry_route);

        /* YForm Rest API */
        $rex_neues_category_route = new rex_yform_rest_route(
            [
                'path' => '/neues/category/5.0.0/',
                'auth' => '\rex_yform_rest_auth_token::checkToken',
                'type' => Category::class,
                'query' => Category::query(),
                'get' => [
                    'fields' => [
                        'FriendsOfRedaxo\Neues\Category' => [
                            'id',
                            'name',
                            'image',
                            'status',
                            'createdate',
                            'createuser',
                            'updatedate',
                            'updateuser',
                            'uuid',
                        ],
                    ],
                ],
                'post' => [
                    'fields' => [
                        'FriendsOfRedaxo\Neues\Category' => [
                            'name',
                            'image',
                            'status',
                            'uuid',
                        ],
                    ],
                ],
                'delete' => [
                    'fields' => [
                        'FriendsOfRedaxo\Neues\Category' => [
                            'id',
                        ],
                    ],
                ],
            ],
        );

        rex_yform_rest::addRoute($rex_neues_category_route);

        /* YForm Rest API */
        $rex_neues_author_route = new rex_yform_rest_route(
            [
                'path' => '/neues/author/5.0.0/',
                'auth' => '\rex_yform_rest_auth_token::checkToken',
                'type' => Author::class,
                'query' => Author::query(),
                'get' => [
                    'fields' => [
                        'FriendsOfRedaxo\Neues\Author' => [
                            'id',
                            'name',
                            'nickname',
                            'text',
                            'image',
                            'be_user_id',
                            'createdate',
                            'createuser',
                            'updatedate',
                            'updateuser',
                            'uuid',
                        ],
                    ],
                ],
                'post' => [
                    'fields' => [
                        'FriendsOfRedaxo\Neues\Author' => [
                            'name',
                            'nickname',
                            'text',
                            'image',
                            'be_user_id',
                        ],
                    ],
                ],
                'delete' => [
                    'fields' => [
                        'FriendsOfRedaxo\Neues\Author' => [
                            'id',
                        ],
                    ],
                ],
            ],
        );

        rex_yform_rest::addRoute($rex_neues_author_route);
    }
}
