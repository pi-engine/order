<?php

namespace Order\Service;

use Content\Service\ItemService;
use IntlDateFormatter;
use Order\Repository\OrderRepositoryInterface;
use User\Service\AccountService;
use User\Service\UtilityService;
use function var_dump;

class OrderService implements ServiceInterface
{
    /* @var OrderRepositoryInterface */
    protected OrderRepositoryInterface $orderRepository;

    protected ItemService $contentItemService;

    protected AccountService $accountService;
    protected PaymentService $paymentService;
    protected UtilityService $utilityService;

    /**
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ItemService              $contentItemService,
        AccountService           $accountService,
        PaymentService           $paymentService,
        UtilityService           $utilityService
    )
    {
        $this->orderRepository = $orderRepository;
        $this->contentItemService = $contentItemService;
        $this->accountService = $accountService;
        $this->paymentService = $paymentService;
        $this->utilityService = $utilityService;
    }

    /**
     * @param $params
     *
     * @return array
     */
    public function getOrderList($params, $account): array|null
    {
        $limit = $params['limit'] ?? 25;
        $page = $params['page'] ?? 1;
        $order = $params['order'] ?? ['time_create DESC', 'id DESC'];
        $offset = ($page - 1) * $limit;
        $contentParams = [
            "order" => $order,
            "offset" => $offset,
            "limit" => $limit,
        ];
        if (isset($params['user_id'])) {
            $contentParams['user_id'] = (int)$params['user_id'];
        }

        $rowSet = $this->orderRepository->getOrderList($contentParams);
        $list = [];
        foreach ($rowSet as $row) {
            $list[] = $this->canonizeOrder($row);
        }
        return [
            'result' => true,
            'data' => [
                'list' => $list,
                'paginator' => [
                    'count' => 0,
                    'limit' => $limit,
                    'page' => $page,
                ],
                'filters' => [],
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
    public function getOrder($params, $account): array
    {
        //        if ($order && !$order['payment']) {
        //            $order['payment'] = $this->paymentService->buildLink($order);
        //            $this->orderRepository->updateOrder(['id' => $order['id'], 'payment' => json_encode($order['payment'])]);
        //        }
        return $this->canonizeOrder($this->orderRepository->getOrder($params));
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
                'slug' => $order->getSlug(),
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
                'payment' => $order->getPayment(),
                'time_create' => $order->getTimeCreate(),
                'total_amount' => $order->getTotalAmount(),
                'information' => $order->getInformation(),
            ];
        } else {
            $order = [
                'id' => (int)$order['id'],
                'slug' => $order['slug'],
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
                'payment' => $order['payment'],
                'time_create' => $order['time_create'],
                'total_amount' => $order['total_amount'],
                'information' => $order['information'],
            ];
        }

        $order['information'] = (!empty($order['information'])) ? json_decode($order['information'], true) : [];
        $order['payment'] = (!empty($order['payment'])) ? json_decode($order['payment'], true) : [];
        $order["time_create_view"] = $this->utilityService->date($order['time_create']);
//        $order["total_amount_view"] = $this->utilityService->setCurrency($order['total_amount']);
        $order["total_amount_view"] = $order['total_amount'] . ' تومان';

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

    public function verifyPayment(array $params, mixed $account): array
    {

        $order = $this->getOrder(['slug' => $params['slug']], $account);

        if (!sizeof($order)) {
            return [
                'result' => false,
                'data' => null,
                'error' => [
                    'code' => 404,
                    'message' => "There is no order with the entered parameters!",
                ],
            ];
        }

        if (!$order['payment'] || $order['payment']['authority'] != $params['authority']) {
            return [
                'result' => false,
                'data' => null,
                'error' => [
                    'code' => 401,
                    'message' => "Payment information is inconsistent with your order information!",
                ],
            ];
        }

        $result = $this->paymentService->verifyPayment($order, $params);
        if ($result["result"]) {
            /// update status of order in first ( if the decode encode maybe has bug and error)
            $this->orderRepository->updateOrder(['id' => $order['id'], 'status' => 'paid']);
            $payment = json_decode($order["payment"],true);
            $payment["result"] = $result;
            $this->orderRepository->updateOrder(['id' => $order['id'], 'status' => 'paid', 'payment' => json_encode($payment)]);
        }
        return $result;

    }

    public function createLink(object|array $params, mixed $account): array
    {

        $order = $this->canonizeOrder($this->orderRepository->getOrder($params));

        if (!sizeof($order)) {
            return [
                'result' => false,
                'data' => null,
                'error' => [
                    'code' => 404,
                    'message' => "There is no order with the entered parameters!",
                ],
            ];
        }

        if ($order['status'] == 'waiting') {
            $order['payment'] = $this->paymentService->buildLink($order);
            $this->orderRepository->updateOrder(['id' => $order['id'], 'payment' => json_encode($order['payment']), 'time_update' => time()]);
            return [
                'result' => true,
                'data' => [
                    "url" => $order['payment']['url']
                ],
                'error' => [],
            ];
        }

        return [
            'result' => true,
            'data' => [
                "url" => "/"
            ],
            'error' => [],
        ];


    }


}
