<?php

namespace Order\Factory\Service;

use Content\Service\ItemService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Order\Service\PaymentService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Order\Repository\OrderRepositoryInterface;
use Order\Service\OrderService;
use User\Service\AccountService;
use User\Service\UtilityService;

class PaymentServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     *
     * @return PaymentService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PaymentService
    {
        $config = $container->get('config');
        return new PaymentService(
            $container->get(OrderRepositoryInterface::class),
//            $container->get(OrderService::class),
            $container->get(ItemService::class),
            $container->get(AccountService::class),
            $container->get(UtilityService::class),
            $config['payment']
        );
    }
}