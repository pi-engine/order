<?php

namespace Order\Handler\Api\Coupon;

use Laminas\Diactoros\Response\JsonResponse;
use Order\Service\CouponService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Order\Service\OrderService;

class CouponVerifyHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var CouponService */
    protected CouponService $couponService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        CouponService          $couponService
    )
    {
        $this->responseFactory  = $responseFactory;
        $this->streamFactory    = $streamFactory;
        $this->couponService  = $couponService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $account = $request->getAttribute('account');
        $requestBody = $request->getParsedBody();
        $params = [
            'code' => $requestBody['code'] ?? '',
            'status' => 1,
        ];
        $result = $this->couponService->verifyCode($params,$account);
        return new JsonResponse($result);
    }
}