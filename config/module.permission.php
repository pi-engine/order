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
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'physical',
            'handler'     => 'create',
            'permission' => 'api-order-physical-list',
            'role'        => [
                'member',
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'physical',
            'handler'     => 'list',
            'permission' => 'api-order-physical-list',
            'role'        => [
                'member',
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'physical',
            'handler'     => 'get',
            'permission' => 'api-order-physical-get',
            'role'        => [
                'member',
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'physical',
            'handler'     => 'update',
            'permission' => 'api-order-physical-update',
            'role'        => [
                'member',
                'admin',
            ],
        ],

        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'payment',
            'handler'     => 'list',
            'permission' => 'api-order-payment-list',
            'role'        => [
                'member',
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'payment',
            'handler'     => 'get',
            'permission' => 'api-order-payment-get',
            'role'        => [
                'member',
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'payment',
            'handler'     => 'verify',
            'permission' => 'api-order-payment-verify',
            'role'        => [
                'member',
                'admin',
            ],
        ],



        [
            'module'      => 'order',
            'section'     => 'admin',
            'package'     => 'list',
            'handler'     => 'list',
            'permission' => 'admin-order-list-list',
            'role'        => [
                'member',
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'admin',
            'package'     => 'list',
            'handler'     => 'get',
            'permission' => 'admin-order-list-get',
            'role'        => [
                'member',
                'admin',
            ],
        ],
    ],
];