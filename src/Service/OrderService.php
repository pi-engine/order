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
        $contentParams = [
            'type' => 'module_order'
        ];
        if (isset($params['user_id'])) {
            $contentParams['user_id'] = $params['user_id'];
        }
        return $this->orderRepository->getOrderList($contentParams);
    }

    /**
     * @param string $parameter
     * @param string $type
     *
     * @return array
     */
    public function getOrder(string $parameter, string $type = 'id'): array
    {
        $order = $this->orderRepository->getOrder($parameter, $type);
        return $this->canonizeOrder($order);
    }


    /// use for reserve
    public function createReserveOrder(array $params, $account): array
    {
        $ordered = $this->contentItemService->getItem($params['item_id'], 'id', ['type' => 'tour']);
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
            'slug' => 'module_order_' . $order->getId() . '_' . $orderParams['slug'],
            'time_create' => time(),
            'status' => 1,
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

//    /**
//     * @param $order
//     *
//     * @return array
//     */
//    public function canonizeOrder($order): array
//    {
//        if (empty($order)) {
//            return [];
//        }
//
//        if (is_object($order)) {
//            $order = [
//                'id' => (int)$order->getId(),
//                'sender_id' => $order->getSenderId(),
//                'receiver_id' => $order->getReceiverId(),
//                'type' => $order->getType(),
//                'status' => $order->getStatus(),
//                'viewed' => $order->getViewed(),
//                'sent' => $order->getSent(),
//                'time_create' => date('Y M d H:i:s', $order->getTimeCreate()),
//                'time_update' => $order->getTimeUpdate(),
//                'information' => $order->getInformation(),
//            ];
//        } else {
//            $order = [
//                'id' => (int)$order['id'],
//                'sender_id' => 3,
//                'receiver_id' => $order['receiver_id'],
//                'type' => $order['type'],
//                'status' => $order['status'],
//                'viewed' => $order['viewed'],
//                'sent' => $order['sent'],
//                'time_create' => date('m/d/Y H:i:s', $order['time_create']),
//                'time_update' => $order['time_update'],
//                'information' => $order['information'],
//            ];
//        }
//
//        // Set information
//        $information = (!empty($order['information'])) ? json_decode($order['information'], true) : [];
//        unset($order['information']);
//
//        return array_merge($order, $information);
//    }

    private function orderSlugGenerator(int $user_id, string $type, int $timestamp): string
    {
        return sprintf('user_%d_%s_%d', $user_id, strtolower(str_replace(' ', '_', $type)), $timestamp);
    }

    public function canonizeOrder($order): array
    {
        if (empty($order)) {
            return [];
        }

        if (is_object($order)) {
            $order = [
                'id' => (int)$order->getId(),
                'user_id' => $order->getUserId(),
                'user' => $this->accountService->getProfile(['user_id' => $order->getUserId()]),
                'entity_type' => $order->getEntityType(),
                'order_type' => $order->getOrderType(),
                'status' => $order->getStatus(),
                'subtotal' => $order->getSubtotal(),
                'tax' => $order->getTax(),
                'discount' => $order->getDiscount(),
                'gift' => $order->getGift(),
                'payment_method' => $order->getPaymentMethod(),
                'create_time' => $order->getTimeCreate(),
                'total_amount' => $order->getTotalAmount(),
                'information' => (!empty($order->getInformation())) ? json_decode($order->getInformation(), true) : [],
            ];
        } else {
            $order = [
                'id' => (int)$order['id'],
                'user_id' => (int)$order['user_id'],
                'user' => $this->accountService->getProfile(['user_id' => $order['user']]),
                'entity_type' => $order['entity_type'],
                'order_type' => $order['order_type'],
                'status' => $order['status'],
                'subtotal' => $order['subtotal'],
                'tax' => $order['tax'],
                'discount' => $order['discount'],
                'gift' => $order['gift'],
                'payment_method' => $order['payment_method'],
                'time_create' => $order['time_create'],
                'total_amount' => $order['total_amount'],
                'information' => (!empty($order['information'])) ? json_decode($order['information'], true) : [],
            ];
        }


        return ($order);
    }

    public function createPhysicalOrder(object|array $params, mixed $account): array
    {
        $order = $this->contentItemService->addOrderItem($params, $account);
        $price = 0;
        if (!sizeof($order)) {
            return [];
        }
        $products = $order['items'];
        foreach ($products as $product)
            $price += $product['price'];
        $params['subtotal'] = $price;
        $params['total_amount'] = $price;
        $params['slug'] = $order['slug'];

        $json = $params;
        $json['cart'] = $order;
        $params['information'] = json_encode($json, JSON_UNESCAPED_UNICODE);
        return $this->canonizeOrder($this->orderRepository->addOrder($params));
    }


}
