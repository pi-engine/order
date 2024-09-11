<?php

namespace Order\Handler\Admin\Coupon;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Order\Service\CouponService;

class CouponUpdateHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var CouponService */
    protected CouponService $discountService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        CouponService $discountService
    ) {
        $this->responseFactory     = $responseFactory;
        $this->streamFactory       = $streamFactory;
        $this->discountService = $discountService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $account = $request->getAttribute('account');
        $requestBody = $request->getParsedBody();
        $result = $this->discountService->updateCoupon($requestBody,$account);
        $responseBody = [
            'result' => true,
            'data' => $result,
            'error' => null,
        ];
        return new JsonResponse($responseBody);
    }
}