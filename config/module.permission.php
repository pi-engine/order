<?php

return [
    'api'   => [
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'reserve',
            'handler'     => 'create',
            'permissions' => 'order-create',
            'role'        => [
                'member',
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'reserve',
            'handler'     => 'list',
            'permissions' => 'order-list',
            'role'        => [
                'member',
                'admin',
            ],
        ],
    ],
];