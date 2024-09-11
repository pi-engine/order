<?php

namespace Order\Repository;

use Laminas\Db\ResultSet\HydratingResultSet;

interface OrderRepositoryInterface
{
    public function getOrderList(array $params = []): HydratingResultSet|array;


    public function addOrder(array $params): object|array;

    public function getOrder($params): object|array;

    public function updateOrder(array $params): object|array;

    public function deleteOrder(array $params): void;

    public function getCustomerCount();

    public function getCoupon($params): object|array;

}