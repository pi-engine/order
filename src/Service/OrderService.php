<?php

namespace Order\Service;

use IntlDateFormatter;
use Order\Repository\OrderRepositoryInterface;
use User\Service\AccountService;
use function var_dump;

class OrderService implements ServiceInterface
{
    /* @var OrderRepositoryInterface */
    protected OrderRepositoryInterface $OrderRepository;

    /**
     * @param OrderRepositoryInterface $OrderRepository
     */
    public function __construct(
        OrderRepositoryInterface $OrderRepository
    )
    {
        $this->OrderRepository = $OrderRepository;
    }

    /**
     * @param $params
     *
     * @return array
     */
    public function getOrderList($params): array
    {
        // Get Orders list
        $limit = $params['limit'] ?? 25;
        $page = $params['page'] ?? 1;
        $order = $params['order'] ?? ['time_create DESC', 'id DESC'];
        $offset = ($page - 1) * $limit;

        // Set params
        $listParams = [
            'page' => $page,
            'limit' => $limit,
            'order' => $order,
            'offset' => $offset,
            'status' => 1,
        ];

        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $listParams['user_id'] = $params['user_id'];
        }

        // Get list
        $list = [];
        $rowSet = $this->OrderRepository->getOrderList($listParams);
        foreach ($rowSet as $row) {
            $list[] = $this->canonizeOrder($row);
        }

        // Get count
        $count = $this->OrderRepository->getOrderCount($listParams);

        return [
            'result' => true,
            'data' => [
                'list' => $list,
                'paginator' => [
                    'count' => $count,
                    'limit' => $limit,
                    'page' => $page,
                ],
            ],
            'error' => [],
        ];
    }

    /**
     * @param string $parameter
     * @param string $type
     *
     * @return array
     */
    public function getOrder(string $parameter, string $type = 'id'): array
    {
        $Order = $this->OrderRepository->getOrder($parameter, $type);
        return $this->canonizeOrder($Order);
    }

    /**
     * @param $params
     */
    public function updateView($params): void
    {
        // Set params
        $updateParams = [
            'id' => $params['id'],
            'user_id' => $params['user_id'],
            'viewed' => 1,
        ];

        $this->OrderRepository->updateOrder($updateParams);
    }


    public function createOrder(array $params): array
    {
        return $params;
    }

    /**
     * @param $Order
     *
     * @return array
     */
    public function canonizeOrder($Order): array
    {
        if (empty($Order)) {
            return [];
        }

        if (is_object($Order)) {
            $Order = [
                'id' => (int)$Order->getId(),
                'sender_id' => $Order->getSenderId(),
                'receiver_id' => $Order->getReceiverId(),
                'type' => $Order->getType(),
                'status' => $Order->getStatus(),
                'viewed' => $Order->getViewed(),
                'sent' => $Order->getSent(),
                'time_create' => date('Y M d H:i:s', $Order->getTimeCreate()),
                'time_update' => $Order->getTimeUpdate(),
                'information' => $Order->getInformation(),
            ];
        } else {
            $Order = [
                'id' => (int)$Order['id'],
                'sender_id' => 3,
                'receiver_id' => $Order['receiver_id'],
                'type' => $Order['type'],
                'status' => $Order['status'],
                'viewed' => $Order['viewed'],
                'sent' => $Order['sent'],
                'time_create' => date('m/d/Y H:i:s', $Order['time_create']),
                'time_update' => $Order['time_update'],
                'information' => $Order['information'],
            ];
        }

        // Set information
        $information = (!empty($Order['information'])) ? json_decode($Order['information'], true) : [];
        unset($Order['information']);

        return array_merge($Order, $information);
    }

}
