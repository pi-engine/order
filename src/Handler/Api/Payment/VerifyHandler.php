<?php

namespace Order\Handler\Api\Payment;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Order\Service\OrderService;

class VerifyHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var OrderService */
    protected OrderService $orderService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        OrderService $orderService
    ) {
        $this->responseFactory     = $responseFactory;
        $this->streamFactory       = $streamFactory;
        $this->orderService = $orderService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Get account
//        $account = $request->getAttribute('account');

        // Get request body
        $requestBody = $request->getParsedBody();

        // Set record params
        $params = [
//            'user_id' => $account['id'],
            'authority'    => $requestBody['authority'] ?? 1,
            'slug'   => $requestBody['slug'] ?? 100,
        ];

        // Get list of Orders
//        $result = $this->orderService->verifyPayment($params,$account);
        $result = $this->orderService->verifyPayment($params,[]);

        return new JsonResponse($result);
    }
}