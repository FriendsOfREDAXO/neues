<?php

namespace FriendsOfRedaxo\Neues;

use rex_yform_rest_route;
use rex_yform_rest;

class RestfulApi {

    public static function init() :void {
        $rex_neues_entry_route = new rex_yform_rest_route(
            [
                'path' => '/neues/entry/5.0.0/',
                'auth' => '\rex_yform_rest_auth_token::checkToken',
                'type' => Entry::class,
                'query' => Entry::query(),
                'get' => [
                    'fields' => [
                        'rex_neues_entry' => [
                            'id',
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
                        'rex_neues_category' => [
                            'id',
                            'name',
                            'image',
                            'status',
                        ],
                        'rex_neues_author' => [
                            'id',
                            'name',
                            'nickname',
                            'text',
                            'image',
                            'be_user_id',
                        ],
                    ],
                ],
                'post' => [
                    'fields' => [
                        'rex_neues_entry' => [
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
                        'rex_neues_entry' => [
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
                        'rex_neues_category' => [
                            'id',
                            'name',
                            'image',
                            'status',
                        ],
                    ],
                ],
                'post' => [
                    'fields' => [
                        'rex_neues_category' => [
                            'name',
                            'image',
                            'status',
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
                        'rex_neues_author' => [
                            'id',
                            'name',
                            'nickname',
                            'text',
                            'image',
                            'be_user_id',
                        ],
                    ],
                ],
                'post' => [
                    'fields' => [
                        'rex_neues_author' => [
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
                        'rex_neues_author' => [
                            'id',
                        ],
                    ],
                ],
            ],
        );
    
        rex_yform_rest::addRoute($rex_neues_author_route);
    
    }

}
