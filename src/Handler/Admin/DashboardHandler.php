<?php

namespace Order\Handler\Admin;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Order\Service\OrderService;

class DashboardHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var OrderService */
    protected OrderService $orderService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        OrderService             $orderService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->orderService = $orderService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Get account
        $account = $request->getAttribute('account');

        // Get request body
        $requestBody = $request->getParsedBody();

        // Set record params
        $requestBody['user_id'] = $account['id'];
        // Get list of Orders
        // Set the response data
        $responseBody =  [
            "result" => true,
            "data" => [
                "box" => [
                    "total_sales" => [
                        "title" => "فروش کل",
                        "description" => "",
                        "value" => "‎ ۷٬۲۳۸٬۷۶۰ تومان",
                        "icon" => "fas fa-coins",
                    ],
                    "total_orders" => [
                        "title" => "تعداد کل سفارش ها",
                        "description" => "",
                        "value" => 460,
                        "icon" => "fas  fa-barcode ",
                    ],
                    "waiting_orders" => [
                        "title" => "تعداد کل سفارش های در انتظار پرداخت",
                        "description" => "",
                        "value" => 160,
                        "icon" => "fas fa-coins",
                    ],
                    "waiting_orders_amount" => [
                        "title" => "مبلغ سفارش های در انتظار پرداخت",
                        "description" => "",
                        "value" => "‎ ۲٬۲۳۸٬۷۶۰ تومان",
                        "icon" => "fas fa-coins",
                    ],
                    "paid_orders" => [
                        "title" => "تعداد کل سفارش های پرداخت شده",
                        "description" => "",
                        "value" => 300,
                        "icon" => "fas fa-coins",
                    ],
                    "customer_count" => [
                        "title" => "تعداد مشتریان",
                        "description" => "",
                        "value" => 400,
                        "icon" => "fas fa-users",
                    ],
                ],
                "daily_sale_chart" => [
                    "labels" => [
                        "۱۴۰۲/۰۹/۰۱",
                        "۱۴۰۲/۰۸/۳۰",
                        "۱۴۰۲/۰۸/۲۹",
                        "۱۴۰۲/۰۸/۲۸",
                        "۱۴۰۲/۰۸/۲۷",
                        "۱۴۰۲/۰۸/۲۶",
                        "۱۴۰۲/۰۸/۲۵",
                        "۱۴۰۲/۰۸/۲۴",
                        "۱۴۰۲/۰۸/۲۳",
                        "۱۴۰۲/۰۸/۲۲",
                        "۱۴۰۲/۰۸/۲۱",
                        "۱۴۰۲/۰۸/۲۰",
                        "۱۴۰۲/۰۸/۱۹",
                        "۱۴۰۲/۰۸/۱۸",
                        "۱۴۰۲/۰۸/۱۷",
                        "۱۴۰۲/۰۸/۱۶",
                        "۱۴۰۲/۰۸/۱۵",
                        "۱۴۰۲/۰۸/۱۴",
                        "۱۴۰۲/۰۸/۱۳",
                        "۱۴۰۲/۰۸/۱۲",
                        "۱۴۰۲/۰۸/۱۱",
                        "۱۴۰۲/۰۸/۱۰",
                        "۱۴۰۲/۰۸/۰۹",
                        "۱۴۰۲/۰۸/۰۸",
                        "۱۴۰۲/۰۸/۰۷",
                        "۱۴۰۲/۰۸/۰۶",
                        "۱۴۰۲/۰۸/۰۵",
                        "۱۴۰۲/۰۸/۰۴",
                        "۱۴۰۲/۰۸/۰۳",
                        "۱۴۰۲/۰۸/۰۲",
                        "۱۴۰۲/۰۸/۰۱",
                        "۱۴۰۲/۰۷/۳۰",
                    ],
                    "data" => [
                        1322500,
                        1016160,
                        1285570,
                        553480,
                        971800,
                        211020,
                        563000,
                        1596120,
                        3231144,
                        201060,
                        968010,
                        2195600,
                        248250,
                        3075244,
                        4964080,
                        1519770,
                        415395,
                        2599947,
                        1104795,
                        76500,
                        1066300,
                        587535,
                        612900,
                        1456880,
                        2639490,
                        2704495,
                        293150,
                        432845,
                        1559995,
                        1354920,
                        776195,
                        702110,
                    ],
                ],
                "daily_order_chart" => [
                    "labels" => [
                        "۱۴۰۲/۰۹/۰۱",
                        "۱۴۰۲/۰۸/۳۰",
                        "۱۴۰۲/۰۸/۲۹",
                        "۱۴۰۲/۰۸/۲۸",
                        "۱۴۰۲/۰۸/۲۷",
                        "۱۴۰۲/۰۸/۲۶",
                        "۱۴۰۲/۰۸/۲۵",
                        "۱۴۰۲/۰۸/۲۴",
                        "۱۴۰۲/۰۸/۲۳",
                        "۱۴۰۲/۰۸/۲۲",
                        "۱۴۰۲/۰۸/۲۱",
                        "۱۴۰۲/۰۸/۲۰",
                        "۱۴۰۲/۰۸/۱۹",
                        "۱۴۰۲/۰۸/۱۸",
                        "۱۴۰۲/۰۸/۱۷",
                        "۱۴۰۲/۰۸/۱۶",
                        "۱۴۰۲/۰۸/۱۵",
                        "۱۴۰۲/۰۸/۱۴",
                        "۱۴۰۲/۰۸/۱۳",
                        "۱۴۰۲/۰۸/۱۲",
                        "۱۴۰۲/۰۸/۱۱",
                        "۱۴۰۲/۰۸/۱۰",
                        "۱۴۰۲/۰۸/۰۹",
                        "۱۴۰۲/۰۸/۰۸",
                        "۱۴۰۲/۰۸/۰۷",
                        "۱۴۰۲/۰۸/۰۶",
                        "۱۴۰۲/۰۸/۰۵",
                        "۱۴۰۲/۰۸/۰۴",
                        "۱۴۰۲/۰۸/۰۳",
                        "۱۴۰۲/۰۸/۰۲",
                        "۱۴۰۲/۰۸/۰۱",
                        "۱۴۰۲/۰۷/۳۰",
                    ],
                    "data" => [
                        1,
                        0,
                        3,
                        5,
                        2,
                        2,
                        3,
                        4,
                        8,
                        6,
                        0,
                        7,
                        1,
                        1,
                        2,
                        1,
                        2,
                        5,
                        4,
                        6,
                        8,
                        1,
                        4,
                        3,
                        0,
                        1,
                        5,
                        4,
                        6,
                        7,
                        2,
                        1,
                    ],
                ],
                "data_from" => "۱۴۰۲/۰۹/۰۱",
                "data_to" => "۱۴۰۲/۰۷/۳۰",
                "day_diff" => 32,
                "week_diff" => 5,
            ],
            "error" => [],
        ];

        return new JsonResponse($responseBody);
    }
}