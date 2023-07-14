<?php

namespace Order;

use Laminas\Mvc\Middleware\PipeSpec;
use Laminas\Router\Http\Literal;
use User\Middleware\AuthenticationMiddleware;
use User\Middleware\AuthorizationMiddleware;
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

            Handler\Api\Reserve\CreateHandler::class => Factory\Handler\Api\Reserve\CreateHandlerFactory::class,
            Handler\Api\Reserve\ListHandler::class => Factory\Handler\Api\Reserve\ListHandlerFactory::class,

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
                                            ///TODO:resolve it when user send json for information
//                                            SecurityMiddleware::class,
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
                                    AuthenticationMiddleware::class,
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