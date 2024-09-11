<?php

namespace Order\Handler\Api\Address;

use Laminas\Diactoros\Response\JsonResponse;
use Order\Service\AddressService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AddressListHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var AddressService */
    protected AddressService $addressService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        AddressService $addressService
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory   = $streamFactory;
        $this->addressService     = $addressService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    { 
        $requestBody = $request->getParsedBody();
        $account     = $request->getAttribute('account');
        $requestBody["user_id"] = $account["id"]; 
        $requestBody["type"] = 'address'; 
        $result = $this->addressService->getAddressList($requestBody);
        return new JsonResponse($result);
    }
}