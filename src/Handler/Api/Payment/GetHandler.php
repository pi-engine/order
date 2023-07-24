<?php

namespace Order\Handler\Api\Payment;

use Laminas\Diactoros\Response\JsonResponse;
use Order\Service\PaymentService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Order\Service\OrderService;

class GetHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var PaymentService */
    protected PaymentService $paymentService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        PaymentService           $paymentService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->paymentService = $paymentService;
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
        $result = $this->paymentService->createLink($requestBody, $account);
        // Set the response data
        $responseBody = [
            'result' => true,
            'data' => $result,
            'error' => null,
        ];

        return new JsonResponse($responseBody);
    }
}