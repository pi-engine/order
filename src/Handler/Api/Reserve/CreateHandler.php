<?php

namespace Order\Handler\Api\Reserve;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Order\Service\OrderService;

class CreateHandler implements RequestHandlerInterface
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
        $params = [
            'user_id' => $account['id'],
            'order_type' => 'reserve',
            'entity_type' => 'tour',
            'ordered_type' => 'tour',
            'persons_count' => (int)$requestBody['count'],
            'persons' => json_decode($requestBody['information'], true),
            'item_id' => $requestBody['item_id'],
            'item_slug' => $requestBody['item_slug'],
        ];

        // Get list of Orders
        $result = $this->orderService->createReserveOrder($params, $account);

        return new JsonResponse($result);
    }
}