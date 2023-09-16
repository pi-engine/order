<?php

namespace Order\Handler\Admin\Status;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Order\Service\OrderService;

class StatusListHandler implements RequestHandlerInterface
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


        $result = [
            [
                'id' => 1,
                'title' => 'در انتظار پرداخت',
                'value' => 'waiting'
            ],
            [
                'id' => 2,
                'title' => 'پرداخت شده',
                'value' => 'paid'
            ],
            [
                'id' => 3,
                'title' => 'در حال بررسی',
                'value' => 'processing'
            ],
            [
                'id' => 4,
                'title' => 'در حال ارسال',
                'value' => 'shipping'
            ],
            [
                'id' => 5,
                'title' => 'تحویل داده شده',
                'value' => 'done'
            ],
        ];
        $responseBody = [
            'result' => true,
            'data' => [
                'list' => $result
            ],
            'error' => null,
        ];
        return new JsonResponse($responseBody);
    }
}