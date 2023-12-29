<?php

namespace Order\Factory\Service;

use Content\Service\ItemService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Order\Service\DiscountService;
use Order\Service\PaymentService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Order\Repository\OrderRepositoryInterface;
use Order\Service\OrderService;
use User\Service\AccountService;
use User\Service\UtilityService;

class DiscountServiceFactory implements FactoryInterface
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