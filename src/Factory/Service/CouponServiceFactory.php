<?php

namespace Order\Factory\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Order\Repository\OrderRepositoryInterface;
use Order\Service\DiscountService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use User\Service\AccountService;
use User\Service\UtilityService;

class CouponServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     *
     * @return DiscountService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): DiscountService
    {
        return new DiscountService(
            $container->get(OrderRepositoryInterface::class),
            $container->get(AccountService::class),
            $container->get(UtilityService::class)
        );
    }
}