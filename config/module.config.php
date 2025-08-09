<?php

namespace Order;

use Laminas\Mvc\Middleware\PipeSpec;
use Laminas\Router\Http\Literal;
use Product\Middleware\CartMiddleware;
use User\Middleware\AuthenticationMiddleware;
use User\Middleware\AuthorizationMiddleware;
use User\Middleware\RequestPreparationMiddleware;
use User\Middleware\SecurityMiddleware;

return [
    'service_manager' => [
        'aliases' => [
            Repository\OrderRepositoryInterface::class => Repository\OrderRepository::class,
        ],
        'factories' => [
            Handler\InstallerHandler::class => Factory\Handler\InstallerHandlerFactory::class,

            Repository\OrderRepository::class => Factory\Repository\OrderRepositoryFactory::class,
            Service\OrderService::class => Factory\Service\OrderServiceFactory::class,
            Service\AddressService::class => Factory\Service\AddressServiceFactory::class,
            Service\PaymentService::class => Factory\Service\PaymentServiceFactory::class,
            Service\CouponService::class => Factory\Service\CouponSErviceFactory::class,

            Handler\Api\Reserve\CreateHandler::class => Factory\Handler\Api\Reserve\CreateHandlerFactory::class,
            Handler\Api\Reserve\ListHandler::class => Factory\Handler\Api\Reserve\ListHandlerFactory::class,

            Handler\Api\Physical\CreateHandler::class => Factory\Handler\Api\Physical\CreateHandlerFactory::class,
            Handler\Api\Physical\ListHandler::class => Factory\Handler\Api\Physical\ListHandlerFactory::class,
            Handler\Api\Physical\GetHandler::class => Factory\Handler\Api\Physical\GetHandlerFactory::class,
            Handler\Api\Physical\UpdateHandler::class => Factory\Handler\Api\Physical\UpdateHandlerFactory::class,

            Handler\Api\Payment\GetHandler::class => Factory\Handler\Api\Payment\GetHandlerFactory::class,
            Handler\Api\Payment\ListHandler::class => Factory\Handler\Api\Payment\ListHandlerFactory::class,
            Handler\Api\Payment\VerifyHandler::class => Factory\Handler\Api\Payment\VerifyHandlerFactory::class,

            Handler\Api\Coupon\CouponVerifyHandler::class => Factory\Handler\Api\Coupon\CouponVerifyHandlerFactory::class,
 
            Handler\Api\Address\AddressAddHandler::class => Factory\Handler\Api\Address\AddressAddHandlerFactory::class,
            Handler\Api\Address\AddressListHandler::class => Factory\Handler\Api\Address\AddressListHandlerFactory::class,


            ///ADMIN
            Handler\Admin\DashboardHandler::class => Factory\Handler\Admin\DashboardHandlerFactory::class,
            Handler\Admin\GetHandler::class => Factory\Handler\Admin\GetHandlerFactory::class,
            Handler\Admin\ListHandler::class => Factory\Handler\Admin\ListHandlerFactory::class,
            Handler\Admin\UpdateHandler::class => Factory\Handler\Admin\UpdateHandlerFactory::class,
            Handler\Admin\Status\StatusListHandler::class => Factory\Handler\Admin\Status\StatusListHandlerFactory::class,
            Handler\Admin\Status\StatusUpdateHandler::class => Factory\Handler\Admin\Status\StatusUpdateHandlerFactory::class,

            Handler\Admin\Coupon\CouponGetHandler::class => Factory\Handler\Admin\Coupon\couponGetHandlerFactory::class,
            Handler\Admin\Coupon\CouponListHandler::class => Factory\Handler\Admin\Coupon\CouponListHandlerFactory::class,
            Handler\Admin\Coupon\CouponUpdateHandler::class => Factory\Handler\Admin\Coupon\CouponUpdateHandlerFactory::class,
            Handler\Admin\Coupon\CouponAddHandler::class => Factory\Handler\Admin\Coupon\CouponAddHandlerFactory::class,


        ],
    ],
    'router' => [
        'routes' => [
            // Api section
            'api_order' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/order',
                    'defaults' => [],
                ],
                'child_routes' => [
                    'address' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/address',
                            'defaults' => [],
                        ],
                        'child_routes' => [
                            'create' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/add',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'address',
                                        'handler' => 'add',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Api\Address\AddressAddHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'list' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/list',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'address',
                                        'handler' => 'list',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Api\Address\AddressListHandler::class
                                        ),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'reserve' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/reserve',
                            'defaults' => [],
                        ],
                        'child_routes' => [
                            'create' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/create',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'reserve',
                                        'handler' => 'create',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Api\Reserve\CreateHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'list' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/list',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'reserve',
                                        'handler' => 'list',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            Handler\Api\Reserve\ListHandler::class
                                        ),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'physical' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/physical',
                            'defaults' => [],
                        ],
                        'child_routes' => [
                            'create' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/create',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'physical',
                                        'handler' => 'create',
                                        'validator'=> 'physical_order',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            CartMiddleware::class,
                                            Handler\Api\Physical\CreateHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'list' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/list',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'physical',
                                        'handler' => 'list',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Api\Physical\ListHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'get' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/get',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'physical',
                                        'handler' => 'get',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Api\Physical\GetHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'update' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/update',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'physical',
                                        'handler' => 'update',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Api\Physical\UpdateHandler::class
                                        ),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'payment' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/payment',
                            'defaults' => [],
                        ],
                        'child_routes' => [
                            'list' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/list',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'payment',
                                        'handler' => 'list',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Api\Payment\ListHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'get' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/get',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'payment',
                                        'handler' => 'get',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Api\Payment\GetHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'verify' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/verify',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'payment',
                                        'handler' => 'verify',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            //AuthenticationMiddleware::class,
                                            //AuthorizationMiddleware::class,
                                            Handler\Api\Payment\VerifyHandler::class
                                        ),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'coupon' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/coupon',
                            'defaults' => [],
                        ],
                        'child_routes' => [
                            'verify' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/verify',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'api',
                                        'package' => 'coupon',
                                        'handler' => 'verify',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Api\Coupon\CouponVerifyHandler::class
                                        ),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            // Admin section
            'admin_order' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/admin/order',
                    'defaults' => [],
                ],
                'child_routes' => [

                    'list' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/list',
                            'defaults' => [
                                'module' => 'order',
                                'section' => 'admin',
                                'package' => 'list',
                                'handler' => 'list',
                                'controller' => PipeSpec::class,
                                'middleware' => new PipeSpec(
                                    SecurityMiddleware::class,
                                    AuthenticationMiddleware::class,
                                    AuthorizationMiddleware::class,
                                    Handler\Admin\ListHandler::class
                                ),
                            ],
                        ],
                    ],
                    'get' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/get',
                            'defaults' => [
                                'module' => 'order',
                                'section' => 'admin',
                                'package' => 'list',
                                'handler' => 'get',
                                'controller' => PipeSpec::class,
                                'middleware' => new PipeSpec(
                                    RequestPreparationMiddleware::class,
                                    SecurityMiddleware::class,
                                    AuthenticationMiddleware::class,
                                    AuthorizationMiddleware::class,
                                    Handler\Admin\GetHandler::class
                                ),
                            ],
                        ],
                    ],
                    'dashboard' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/dashboard',
                            'defaults' => [
                                'module' => 'order',
                                'section' => 'admin',
                                'package' => 'list',
                                'handler' => 'get',
                                'controller' => PipeSpec::class,
                                'middleware' => new PipeSpec(
                                    SecurityMiddleware::class,
                                    AuthenticationMiddleware::class,
                                    AuthorizationMiddleware::class,
                                    Handler\Admin\DashboardHandler::class
                                ),
                            ],
                        ],
                    ],
                    'update' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/update',
                            'defaults' => [
                                'module' => 'order',
                                'section' => 'admin',
                                'package' => 'list',
                                'handler' => 'update',
                                'controller' => PipeSpec::class,
                                'middleware' => new PipeSpec(
                                    RequestPreparationMiddleware::class,
                                    SecurityMiddleware::class,
                                    AuthenticationMiddleware::class,
                                    AuthorizationMiddleware::class,
                                    Handler\Admin\UpdateHandler::class
                                ),
                            ],
                        ],
                    ],
                    'status' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/status',
                            'defaults' => [],
                        ],
                        'child_routes' => [
                            'list' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/list',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'admin',
                                        'package' => 'list',
                                        'handler' => 'list',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Admin\Status\StatusListHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'update' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/update',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'admin',
                                        'package' => 'status',
                                        'handler' => 'update',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Admin\Status\StatusUpdateHandler::class
                                        ),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'coupon' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/coupon',
                            'defaults' => [],
                        ],
                        'child_routes' => [
                            'get' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/get',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'admin',
                                        'package' => 'coupon',
                                        'handler' => 'list',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Admin\Coupon\CouponGetHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'list' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/list',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'admin',
                                        'package' => 'coupon',
                                        'handler' => 'list',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Admin\Coupon\CouponListHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'add' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/add',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'admin',
                                        'package' => 'coupon',
                                        'handler' => 'add',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Admin\Coupon\CouponAddHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'update' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/update',
                                    'defaults' => [
                                        'module' => 'order',
                                        'section' => 'admin',
                                        'package' => 'coupon',
                                        'handler' => 'update',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            SecurityMiddleware::class,
                                            AuthenticationMiddleware::class,
                                            AuthorizationMiddleware::class,
                                            Handler\Admin\Coupon\CouponUpdateHandler::class
                                        ),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    // Admin installer
                    'installer' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/installer',
                            'defaults' => [
                                'module' => 'Order',
                                'section' => 'admin',
                                'package' => 'installer',
                                'handler' => 'installer',
                                'controller' => PipeSpec::class,
                                'middleware' => new PipeSpec(
                                    SecurityMiddleware::class,
                                    //AuthenticationMiddleware::class,
                                    Handler\InstallerHandler::class
                                ),
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];