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

        // Get list of Orders
        // Set the response data
        $responseBody =  [
            "result" => true,
            "data" => [
                "box" => [
                    "total_sales" => [
                        "title" => "فروش کل",
                        "description" => "",
                        "value" => $this->orderService->getTotalSale(['status'=>['paid','done']],$account),
                        "icon" => "fas fa-coins",
                    ],
                    "total_orders" => [
                        "title" => "تعداد کل سفارش ها",
                        "description" => "",
                        "value" => $this->orderService->getOrderList($requestBody,$account)['data']['paginator']['count'],
                        "icon" => "fas  fa-barcode ",
                    ],
                    "waiting_orders" => [
                        "title" => "تعداد کل سفارش های در انتظار پرداخت",
                        "description" => "",
                        "value" =>  $this->orderService->getOrderList(['status'=>'waiting'],$account)['data']['paginator']['count'],
                        "icon" => "fas fa-coins",
                    ],
                    "waiting_orders_amount" => [
                        "title" => "مبلغ سفارش های در انتظار پرداخت",
                        "description" => "",
                        "value" =>  $this->orderService->getTotalSale(['status'=>['waiting']],$account),
                        "icon" => "fas fa-coins",
                    ],
                    "paid_orders" => [
                        "title" => "تعداد کل سفارش های پرداخت شده",
                        "description" => "",
                        "value" =>  $this->orderService->getOrderList(['status'=>['paid','done']],$account)['data']['paginator']['count'],
                        "icon" => "fas fa-coins",
                    ],
                    "customer_count" => [
                        "title" => "تعداد مشتریان",
                        "description" => "",
                        "value" => $this->orderService->getCustomerCount($requestBody),
                        "icon" => "fas fa-users",
                    ],
                ],
                "daily_sale_chart" => $this->orderService->getDailySaleChart($requestBody),
                "daily_order_chart"   => $this->orderService->getDailyOrderChart($requestBody),
                "data_from" => $this->orderService->getDate()['data_from'],
                "data_to" => $this->orderService->getDate()['data_to'],
                "day_diff" => 31,
                "week_diff" => 5,
            ],
            "error" => [],
        ];

        return new JsonResponse($responseBody);
    }
}