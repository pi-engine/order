<?php

namespace Order\Factory\Service;

use Content\Service\ItemService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Notification\Service\NotificationService;
use Order\Service\DiscountService;
use Order\Service\PaymentService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Order\Repository\OrderRepositoryInterface;
use Order\Service\OrderService;
use User\Service\AccountService;
use User\Service\UtilityService;

class OrderServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     *
     * @return OrderService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): OrderService
    {
        $config = $container->get('config');
        return new OrderService(
            $container->get(OrderRepositoryInterface::class),
            $container->get(ItemService::class),
            $container->get(AccountService::class),
            $container->get(PaymentService::class),
            $container->get(DiscountService::class),
            $container->get(NotificationService::class),
            $container->get(UtilityService::class),
            $config['payment']
        );
    }
}