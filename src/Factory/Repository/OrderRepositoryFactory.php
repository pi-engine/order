<?php

namespace Order\Factory\Repository;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Order\Model\Order;
use Order\Model\OrderItem;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Order\Repository\OrderRepository;

class OrderRepositoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return OrderRepository
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): OrderRepository
    {
        return new OrderRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Order(0, '', 0, '', '', 0, 0, 0, 0, 0, [], 0, 0, 0),
            new OrderItem(
                0,
                0,
                0,
                0,
                0,
                0, 0, 0, 0, 0, 0, [], 0, 0, 0),
        );
    }
}