<?php

namespace Order\Repository;

use Laminas\Db\ResultSet\HydratingResultSet;

interface OrderRepositoryInterface
{
    public function getOrderList(array $params = []): HydratingResultSet|array;

    public function getOrderCount(array $params = []): int;
    public function getUnreadOrderCount(array $params = []): int;

    public function addOrder(array $params): object|array;

    public function getOrder($parameter, $type = 'id'): object|array;

    public function updateOrder(array $params): object|array;

    public function deleteOrder(array $params): void;
}