<?php

return [
    'api'   => [
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'coupon',
            'handler'     => 'verify',
            'permissions' => 'api-order-coupon-verify',
            'role'        => [
                'member',
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'address',
            'handler'     => 'list',
            'permissions' => 'api-order-address-list',
            'role'        => [
                'member',
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'address',
            'handler'     => 'add',
            'permissions' => 'api-order-address-add',
            'role'        => [
                'member',
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'api',
            'package'     => 'discount',
            'handler'     => 'verify',
            'permissions' => 'api-order-discount-verify',
            'role'        => [
                'member',
                'admin',
            ],
        ],
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
    'admin'   => [
        [
            'module'      => 'order',
            'section'     => 'admin',
            'package'     => 'discount',
            'handler'     => 'update',
            'permissions' => 'admin-order-discount-update',
            'role'        => [
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'admin',
            'package'     => 'discount',
            'handler'     => 'add',
            'permissions' => 'admin-order-discount-add',
            'role'        => [
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'admin',
            'package'     => 'discount',
            'handler'     => 'list',
            'permissions' => 'admin-order-discount-list',
            'role'        => [
                'admin',
            ],
        ],
        [
            'module'      => 'order',
            'section'     => 'admin',
            'package'     => 'status',
            'handler'     => 'update',
            'permissions' => 'admin-order-status-update',
            'role'        => [
                'admin',
            ],
        ],
    ],
];