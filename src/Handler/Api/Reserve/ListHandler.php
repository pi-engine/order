<?php

namespace Order\Handler\Api\Reserve;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Order\Service\OrderService;

class ListHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var OrderService */
    protected OrderService $OrderService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        OrderService $OrderService
    ) {
        $this->responseFactory     = $responseFactory;
        $this->streamFactory       = $streamFactory;
        $this->OrderService = $OrderService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Get account
        $account = $request->getAttribute('account');

        // Get request body
        $requestBody = $request->getParsedBody();

        // Set record params
        $params = [
            'user_id' => $account['id'],
            'page'    => $requestBody['page'] ?? 1,
            'limit'   => $requestBody['limit'] ?? 25,
        ];

        // Get list of Orders
//        $result = $this->OrderService->getOrderList($params);

        return new JsonResponse([]);
    }
}