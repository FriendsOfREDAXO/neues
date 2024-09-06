<?php

namespace FriendsOfRedaxo\Neues\Api;

use FriendsOfRedaxo\Neues\Author;
use FriendsOfRedaxo\Neues\Category;
use FriendsOfRedaxo\Neues\Entry;
use rex_yform_rest;
use rex_yform_rest_route;

class Restful
{
    public static function init(): void
    {
        $rex_neues_entry_route = new rex_yform_rest_route(
            [
                'path' => '/neues/entry/5.0.0/',
                'auth' => '\rex_yform_rest_auth_token::checkToken',
                'type' => Entry::class,
                'query' => Entry::query()->where('status', 1),
                'get' => [
                    'fields' => [
                        Entry::class => [
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
                        Category::class => [
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
                        Author::class => [
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
                        Entry::class => [
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
                        Entry::class => [
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
                'query' => Category::query()->where('status', 1),
                'get' => [
                    'fields' => [
                        Category::class => [
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
                        Category::class => [
                            'name',
                            'image',
                            'status',
                            'uuid',
                        ],
                    ],
                ],
                'delete' => [
                    'fields' => [
                        Category::class => [
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
                        Author::class => [
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
                        Author::class => [
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
                        Author::class => [
                            'id',
                        ],
                    ],
                ],
            ],
        );

        rex_yform_rest::addRoute($rex_neues_author_route);
    }
}
