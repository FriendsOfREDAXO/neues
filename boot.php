<?php
rex_yform_manager_dataset::setModelClass(
    'rex_neues_entry',
    neues_entry::class
);
rex_yform_manager_dataset::setModelClass(
    'rex_event_category',
    event_category::class
);

if (rex_addon::get('cronjob')->isAvailable() && !rex::isSafeMode()) {
    rex_cronjob_manager::registerType('rex_cronjob_events_ics_import');
}

if (rex_plugin::get('yform', 'rest')->isAvailable() && !rex::isSafeMode()) {

/* YForm Rest API */
    $rex_neues_entry_route = new \rex_yform_rest_route(
        [
        'path' => '/v0.dev/event/date/',
        'auth' => '\rex_yform_rest_auth_token::checkToken',
        'type' => \neues_entry::class,
        'query' => \neues_entry::query(),
        'get' => [
            'fields' => [
                'rex_neues_entry' => [
                    'id',
                    'name',
                    'description',
                    'image',
                    'endDate',
                    'status',
                    'url'
                 ],
                 'rex_event_category' => [
                    'id',
                    'name',
                    'image'
                 ]
            ]
        ],
        'post' => [
            'fields' => [
                'rex_neues_entry' => [
                    'name',
                    'description',
                    'image',
                    'url'
                ]
            ]
        ],
        'delete' => [
            'fields' => [
                'rex_neues_entry' => [
                    'id'
                ]
            ]
        ]
    ]
    );

    \rex_yform_rest::addRoute($rex_neues_entry_route);


    /* YForm Rest API */
    $rex_event_category_route = new \rex_yform_rest_route(
        [
        'path' => '/v0.dev/event/category/',
        'auth' => '\rex_yform_rest_auth_token::checkToken',
        'type' => \event_category::class,
        'query' => \event_category::query(),
        'get' => [
            'fields' => [
                 'rex_event_category' => [
                    'id',
                    'name',
                    'image'
                 ]
            ]
        ],
        'post' => [
            'fields' => [
                'rex_event_category' => [
                    'name',
                    'image'
                ]
            ]
        ],
        'delete' => [
            'fields' => [
                'rex_event_category' => [
                    'id'
                ]
            ]
        ]
    ]
    );

    \rex_yform_rest::addRoute($rex_event_category_route);
};

rex_extension::register('REX_YFORM_SAVED', function (rex_extension_point $ep) {

    // darf nur bei passender Tabelle passieren.
//    $id = $ep->getParam('id');
//    $dataset = neues_entry::get($ep->getParam('id'));
//    rex_sql::factory()->setQuery("UPDATE rex_neues_entry SET uid = :uid WHERE id = :id", [":uid"=>$dataset->getUid(), ":id" => $id]);

});