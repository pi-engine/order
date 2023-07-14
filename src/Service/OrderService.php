<?php

namespace Order\Service;

use Content\Service\ItemService;
use IntlDateFormatter;
use Order\Repository\OrderRepositoryInterface;
use User\Service\AccountService;
use function var_dump;

class OrderService implements ServiceInterface
{
    /* @var OrderRepositoryInterface */
    protected OrderRepositoryInterface $orderRepository;

    protected ItemService $contentItemService;

    protected AccountService $accountService;

    /**
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ItemService              $contentItemService,
        AccountService           $accountService
    )
    {
        $this->orderRepository = $orderRepository;
        $this->contentItemService = $contentItemService;
        $this->accountService = $accountService;
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
        $rowSet = $this->orderRepository->getOrderList($listParams);
        foreach ($rowSet as $row) {
            $list[] = $this->canonizeOrder($row);
        }

        // Get count
        $count = $this->orderRepository->getOrderCount($listParams);

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
        $Order = $this->orderRepository->getOrder($parameter, $type);
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

        $this->orderRepository->updateOrder($updateParams);
    }


    /// use for reserve
    public function createReserveOrder(array $params, $account): array
    {
        $ordered = $this->contentItemService->getItem($params['item_id']);
        $orderParams = [
            'user_id' => $params['user_id'],
            'slug' => $this->orderSlugGenerator($params['user_id'], $params['type'], time()),
            'type' => $params['type'],
            'information' => json_encode(['order_history' => ['time_create' => time()]]),
            'time_create' => time()
        ];
        $order = $this->orderRepository->addOrder($orderParams);

        $orderItemParams = [
            'user_id' => $params['user_id'],
            'order_id' => $order->getId(),
            'ordered_id' => $params['item_id'],
            'ordered_slug' => $params['item_slug'],
            'ordered_type' => $params['ordered_type'],
            'quantity' => $params['persons_count'],
            'information' => json_encode($params['persons']),
            'time_create' => time()
        ];

        $orderItem = $this->orderRepository->addOrderItem($orderItemParams);

        $content = [
            'user_id' => $params['user_id'],
            'type' => 'module_order',
            'slug' => 'module_order_' . $order->getId().'_' . $orderParams['slug'],
            'time_create' => time(),
            'information' => json_encode([
                'user_id' => $params['user_id'],
                'user' => $this->accountService->getAccount(['id' => $params['user_id']]),
                'user_profile' => $this->accountService->getProfile(['user_id' => $params['user_id']]),
                'type' => 'module_order',
                'slug' => 'module_order_' . $orderParams['slug'],
                'time_create' => time(),
                'order_id' => $order->getId(),
                'ordered_id' => $params['item_id'],
                'ordered_slug' => $params['item_slug'],
                'ordered_type' => $params['ordered_type'],
                'order_type' => $params['type'],
                'quantity' => $params['persons_count'],
                'ordered' => $ordered,
                'persons' => $params['persons']
            ])
        ];
        $this->contentItemService->addItem($content, $account);
        ///TODO:store this order to content module
        return [$orderItem->getId()];
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

    private function orderSlugGenerator(int $user_id, string $type, int $timestamp): string
    {
        return sprintf('user_%d_%s_%s', $user_id, strtolower(str_replace(' ', '_', $type)), date('Ymd', $timestamp));
    }


}
