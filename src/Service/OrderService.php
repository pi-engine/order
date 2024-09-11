<?php

namespace Order\Service;

use Content\Service\ItemService;
use IntlDateFormatter;
use Notification\Service\NotificationService;
use Order\Repository\OrderRepositoryInterface;
use Product\Service\CartService;
use stdClass;
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
    protected CouponService $couponService;

    protected NotificationService $notificationService;

    protected UtilityService $utilityService;

    protected CartService $cartService;

    protected AddressService $addressService;

    protected array $config;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param ItemService $contentItemService
     * @param AccountService $accountService
     * @param PaymentService $paymentService
     * @param CouponService $couponService
     * @param NotificationService $notificationService
     * @param UtilityService $utilityService
     * @param CartService $cartService
     * @param AddressService $addressService
     * @param $config
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ItemService              $contentItemService,
        AccountService           $accountService,
        PaymentService           $paymentService,
        CouponService          $couponService,
        NotificationService      $notificationService,
        UtilityService           $utilityService,
        CartService              $cartService,
        AddressService            $addressService,
                                 $config

    )
    {
        $this->orderRepository = $orderRepository;
        $this->contentItemService = $contentItemService;
        $this->accountService = $accountService;
        $this->paymentService = $paymentService;
        $this->notificationService = $notificationService;
        $this->couponService = $couponService;
        $this->cartService = $cartService;
        $this->utilityService = $utilityService;
        $this->addressService = $addressService;
        $this->config = $config;
    }

    /**
     * @param $params
     * @param $account
     * @return array|null
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
        if (isset($params['id']) && !empty($params['id'])) {
            $contentParams['id'] = (int)$params['id'];
        }
        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $contentParams['user_id'] = explode(',', $params['user_id']);
        }
        if (isset($params['status']) && !empty($params['status'])) {
            $contentParams['status'] = $params['status'];
        }
        if (isset($params['payment_method']) && !empty($params['payment_method'])) {
            $contentParams['payment_method'] = $params['payment_method'];
        }
        if (isset($params['ref_id']) && !empty($params['ref_id'])) {
            $contentParams['ref_id'] = $params['ref_id'];
        }
        if (isset($params['postal_code']) && !empty($params['postal_code'])) {
            $contentParams['postal_code'] = $params['postal_code'];
        }
        if (isset($params['name']) && !empty($params['name'])) {
            $contentParams['name'] = $params['name'];
        }
        if (isset($params['phone']) && !empty($params['phone'])) {
            $contentParams['phone'] = $params['phone'];
        }
        if (isset($params['address']) && !empty($params['address'])) {
            $contentParams['address'] = $params['address'];
        }
        if (isset($params['product']) && !empty($params['product'])) {
            $contentParams['product'] = $params['product'];
        }

        if (isset($params['data_from'])) {
            $contentParams['data_from'] = strtotime(
                ($params['data_from']) != null
                    ? sprintf('%s 00:00:00', $params['data_from'])
                    : sprintf('%s 00:00:00', date('Y-m-d', strtotime('-1 month')))
            );
        }

        if (isset($params['data_to'])) {
            $contentParams['data_to'] = strtotime(
                ($params['data_to']) != null
                    ? sprintf('%s 00:00:00', $params['data_to'])
                    : sprintf('%s 23:59:59', date('Y-m-d'))
            );
        }


        $rowSet = $this->orderRepository->getOrderList($contentParams);
        $list = [];
        foreach ($rowSet as $row) {
            $list[] = $this->canonizeOrder($row);
        }
        $count = $this->orderRepository->getOrderCount($contentParams);
        return [
            'result' => true,
            'data' => [
                'list' => $list,
                'paginator' => [
                    'count' => $count,
                    'limit' => $limit,
                    'page' => $page,
                ],
                'filters' => [],
            ],
            'error' => [],
        ];
    }

    /**
     * @param $params
     * @param $account
     * @return array|null
     */
    public function getReserveOrderList($params, $account): array|null
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
        $contentParams['type'] = 'module_order';

        $originalList = $this->contentItemService->getItemList($contentParams);

        if (isset($originalList['data'])) {
            if (isset($originalList['data']['list'])) {
                $orderList = $originalList['data']['list'];
                for ($i = 0; $i < sizeof($orderList); $i++) {
                    $orderList[$i]['time_create_view'] = $this->utilityService->date($orderList[$i]['time_create']);
                }
                $originalList['data']['list'] = $orderList;
            }
        }

        return $originalList;

    }


    /**
     * @param $params
     * @param $account
     * @return array
     */
    public function getOrder($params, $account): array
    {
        return $this->canonizeOrder($this->orderRepository->getOrder($params));
    }


    /// use for reserve
    public function createReserveOrder(array $params, $account): array
    {
        $ordered = $this->contentItemService->getItem($params['item_id'], 'id', ['type' => 'tour']);
        $orderParams = [
            'user_id' => $params['user_id'],
            'slug' => $this->orderSlugGenerator($params['user_id'], $params['order_type'], time()),
            'order_type' => $params['order_type'],
            'information' => json_encode([
                    'order_history' => ['time_create' => time()],
                    'ordered' => ($ordered),
                    'persons_count' => $params['persons_count'],
                    'persons' => $params['persons'],
                    'item_id' => $params['item_id'],
                    'item_slug' => $params['item_slug'],
                ]
            ),
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

    public function createPhysicalOrder(object|array $requestBody, mixed $account): array
    {

        $discount = 0;
        $gift = '';

        $params = [
            'user_id' => $account['id'],
            'order_type' => 'physical',
            'entity_type' => $requestBody['entity_type'] ?? 'product',
            'payment_method' => $requestBody['payment_method'] ?? 'online',
            'time_create' => time(),
        ];

        $requestBody['user_id'] = $account['id'];
        $requestBody['order_type'] = 'physical';
        $requestBody['entity_type'] = $requestBody['entity_type'] ?? 'product';
        $requestBody['payment_method'] = $requestBody['payment_method'] ?? 'online';
        $requestBody['time_create'] = time();

        //get cart information
        $cart =$this->cartService->getCart($account);

        //get coupon information
        $discountData = [];
        if (isset($requestBody['coupon'])) {
            $discountData = $this->couponService->getCoupon(['code' => $requestBody['coupon']], $account);
            if (!empty($discountData)) {
                $discount = $discountData['value'];
                $gift = $discountData['code'];
                unset($discountData['time_create_view']);
                unset($discountData['time_expired_view']);
                $this->couponService->useCoupon($discountData, $account);
            }
        }

        //get address information
        $address= [];
        if (isset($requestBody['address'])) {
            if(isset($requestBody['address']['id'])&&$requestBody['address']['id']!==null){
                $address = $requestBody['address'];
            }else{
                $address = $this->addressService->addAddress($requestBody['address'], $account);
            }
        }

        $price =(int)$cart['cart']['payable_price'];
        $params['subtotal'] = $price;
        $params['total_amount'] = ($price - (($discount * $price) / 100));
        $params['slug'] = $this->orderSlugGenerator($cart['user_id'],'physical',time());
        $params['discount'] = $discount;
        $params['coupon_id'] = empty($discountData)?null:$discountData['id'];
        $params['gift'] = $gift;

        $json = $params;

        //unset old params of price in cart and show only order price
        unset($cart['cart']['total_price']);
        unset($cart['cart']['payable_price']);

        $json['cart'] = $cart['cart'];
        $json['coupon'] = $discountData;
        $json['address'] = $address;

        //data as time delivery and extra info
        $json['delivery_information'] = isset($requestBody['delivery_information']) ? $requestBody['delivery_information'] : [];
        $params['information'] = json_encode($json, JSON_UNESCAPED_UNICODE);
        $result = $this->canonizeOrder($this->orderRepository->addOrder($params));

        if($result){
            $this->cartService->clearCart($account);
        }
        return [
            'result' => true,
            'data' => $result,
            'error' => [],
        ];
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
            if ($order['status'] != 'paid') {
                /// update status of order in first ( if the decode encode maybe has bug and error)
                $this->orderRepository->updateOrder(['id' => $order['id'], 'status' => 'paid']);
                $payment["result"] = $result;
                $this->orderRepository->updateOrder(['id' => $order['id'], 'status' => 'paid', 'payment' => json_encode($payment)]);
                if (isset($order['information'])) {
                    if (isset($order['information']['cart'])) {
                        if (isset($order['information']['cart']['items'])) {
                            $products = $order['information']['cart']['items'];
                            foreach ($products as $product) {
                                $originalProduct = $this->contentItemService->getItem($product['slug'], 'slug');
                                $oldStock = $this->getStockCount($originalProduct);
                                $newStockValue = ((int)$oldStock > (int)$product['count']) ? ((int)$oldStock - (int)$product['count']) : 0;
                                for ($i = 0; $i < sizeof($originalProduct['meta']); $i++) {
                                    if ($originalProduct['meta'][$i]['meta_key'] == 'stock') {
                                        $originalProduct['meta'][$i]['meta_value'] = $newStockValue;
                                        break; // Break the loop if 'stock' is found
                                    }
                                }
                                $originalProduct['mode'] = 'entity';
                                $originalProduct['status'] = 1;
                                $this->contentItemService->updateEntity($originalProduct, $account);
                            }
                        }
                    }
                }

                if ($this->config['notices']['admin']['text'] != '') {
                    $notificationParams = [
                        'sms' => [
                            'message' => $this->config['notices']['admin']['text'],
                            'mobile' => $this->config['notices']['admin']['mobile'],
                            'source' => '',
                        ],
                    ];
                    $this->notificationService->send($notificationParams);
                }
                $client = $this->accountService->getAccount(['id' => $order['user_id']]);
                if (!empty($client)) {
                    if (isset($client['mobile'])) {
                        if ($this->config['notices']['client']['text'] != '') {
                            $notificationParams = [
                                'sms' => [
                                    'message' => $this->config['notices']['client']['text'],
                                    'mobile' => $client['mobile'],
                                    'source' => '',
                                ],
                            ];
                            $this->notificationService->send($notificationParams);
                        }
                    }
                }


            }

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

    public function updateOrder(array $params, mixed $account): array
    {
        $order['status'] = $params['status'] ?? 'unknown';
        $order['id'] = $params['id'];
        $order['time_update'] = time();
        $this->orderRepository->updateOrder($order);
        return $this->getOrder($params, $account);
    }

    /// update meta price from last price when change cart to order
    private function getPrice(mixed $product)
    {
        $price = 0;
        if (isset($product['price'])) {
            $price = $product['price'];
        }
        if (isset($product['property'])) {
            if (isset($product['property']['meta_selected_data'])) {
                foreach ($product['property']['meta_selected_data'] as $meta) {
                    if ($meta["meta_key"] == "price") {
                        $price = (int)($meta["meta_value"]);
                    }
                }
            }
        }
        return $price;
    }

    private function getStockCount($item)
    {
        $stock = 0;

        if (isset($item['meta'])) {
            if (count($item['meta']) > 0) {
                foreach ($item['meta'] as $meta) {
                    if ($meta['meta_key'] == 'stock') {
                        $stock = $meta['meta_value'];
                        break; // Break the loop if 'stock' is found
                    }
                }
            }
        }
        return $stock;
    }

    public function getTotalSale($params, $account): float|int|string
    {
        $limit = $params['limit'] ?? 1000000;
        $page = $params['page'] ?? 1;
        $order = $params['order'] ?? ['time_create DESC', 'id DESC'];
        $offset = ($page - 1) * $limit;
        $contentParams = [
            "order" => $order,
            "offset" => $offset,
            "limit" => $limit,
        ];
        if (isset($params['id']) && !empty($params['id'])) {
            $contentParams['id'] = (int)$params['id'];
        }
        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $contentParams['user_id'] = explode(',', $params['user_id']);
        }
        if (isset($params['status']) && !empty($params['status'])) {
            $contentParams['status'] = $params['status'];
        }
        if (isset($params['payment_method']) && !empty($params['payment_method'])) {
            $contentParams['payment_method'] = $params['payment_method'];
        }
        if (isset($params['ref_id']) && !empty($params['ref_id'])) {
            $contentParams['ref_id'] = $params['ref_id'];
        }
        if (isset($params['postal_code']) && !empty($params['postal_code'])) {
            $contentParams['postal_code'] = $params['postal_code'];
        }
        if (isset($params['name']) && !empty($params['name'])) {
            $contentParams['name'] = $params['name'];
        }
        if (isset($params['phone']) && !empty($params['phone'])) {
            $contentParams['phone'] = $params['phone'];
        }
        if (isset($params['address']) && !empty($params['address'])) {
            $contentParams['address'] = $params['address'];
        }
        if (isset($params['product']) && !empty($params['product'])) {
            $contentParams['product'] = $params['product'];
        }

        $rowSet = $this->orderRepository->getOrderList($contentParams);
        $totalAmount = 0;
        foreach ($rowSet as $row) {
            $row = $this->canonizeOrder($row);
            $totalAmount = $totalAmount + $row['total_amount'];
        }
        return $this->utilityService->setCurrency($totalAmount);
    }

    public function getCustomerCount($params)
    {
        $limit = $params['limit'] ?? 1000000;
        $page = $params['page'] ?? 1;
        $order = $params['order'] ?? ['time_create DESC', 'id DESC'];
        $offset = ($page - 1) * $limit;
        $contentParams = [
            "order" => $order,
            "offset" => $offset,
            "limit" => $limit,
        ];
        if (isset($params['id']) && !empty($params['id'])) {
            $contentParams['id'] = (int)$params['id'];
        }
        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $contentParams['user_id'] = explode(',', $params['user_id']);
        }
        if (isset($params['status']) && !empty($params['status'])) {
            $contentParams['status'] = $params['status'];
        }
        if (isset($params['payment_method']) && !empty($params['payment_method'])) {
            $contentParams['payment_method'] = $params['payment_method'];
        }
        if (isset($params['ref_id']) && !empty($params['ref_id'])) {
            $contentParams['ref_id'] = $params['ref_id'];
        }
        if (isset($params['postal_code']) && !empty($params['postal_code'])) {
            $contentParams['postal_code'] = $params['postal_code'];
        }
        if (isset($params['name']) && !empty($params['name'])) {
            $contentParams['name'] = $params['name'];
        }
        if (isset($params['phone']) && !empty($params['phone'])) {
            $contentParams['phone'] = $params['phone'];
        }
        if (isset($params['address']) && !empty($params['address'])) {
            $contentParams['address'] = $params['address'];
        }
        if (isset($params['product']) && !empty($params['product'])) {
            $contentParams['product'] = $params['product'];
        }
        return $this->orderRepository->getCustomerCount($contentParams);
    }

    public function getDailyOrderChart($params): array
    {

        $params['data_from'] = date('Y-m-d', strtotime('-30 days'));
        $params['data_to'] = date('Y-m-d', time());
        $list = $this->getOrderList($params, [])['data']['list'];
        $groupedData = [];

        // Initialize $groupedData with zero values for each day of the last 30 days
        $today = date('Y-m-d');
        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));

        $currentDay = strtotime($thirtyDaysAgo);

        while ($currentDay <= strtotime($today)) {
            $dayKey = date('Y-m-d', $currentDay);

            $dayKey = explode(' ', $this->utilityService->date(strtotime($dayKey)))[0];
            $groupedData[$dayKey] = [
                'count' => 0,
                'total_amount' => 0,
            ];
            $currentDay = strtotime('+1 day', $currentDay);
        }

// Process your actual data
        foreach ($list as $item) {
            // Convert the timestamp to a date string with the day only
//            $day = date('Y-m-d', $item['time_create']);
            $day = explode(' ', $this->utilityService->date($item['time_create']))[0];

            // Increment the count and update the total_amount
            $groupedData[$day]['count']++;
            $groupedData[$day]['total_amount'] += $item['total_amount'];
        }

// Sort by date
        ksort($groupedData);
        return [
            'labels' => array_keys($groupedData),
            'data' => array_column($groupedData, 'count')
        ];

    }

    public function getDailySaleChart($params): array
    {

        $params['data_from'] = date('Y-m-d', strtotime('-30 days'));
        $params['data_to'] = date('Y-m-d', time());
        $list = $this->getOrderList($params, [])['data']['list'];
        $groupedData = [];

        // Initialize $groupedData with zero values for each day of the last 30 days
        $today = date('Y-m-d');
        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));

        $currentDay = strtotime($thirtyDaysAgo);

        while ($currentDay <= strtotime($today)) {
            $dayKey = date('Y-m-d', $currentDay);

            $dayKey = explode(' ', $this->utilityService->date(strtotime($dayKey)))[0];
            $groupedData[$dayKey] = [
                'count' => 0,
                'total_amount' => 0,
            ];
            $currentDay = strtotime('+1 day', $currentDay);
        }

        foreach ($list as $item) {
            $day = explode(' ', $this->utilityService->date($item['time_create']))[0];

            // Increment the count and update the total_amount
            $groupedData[$day]['count']++;
            $groupedData[$day]['total_amount'] += $item['total_amount'];
        }

        ksort($groupedData);
        return [
            'labels' => array_keys($groupedData),
            'data' => array_column($groupedData, 'total_amount')
        ];

    }

    public function getDate(): array
    {
        $params['data_from'] = explode(' ', $this->utilityService->date(strtotime('-30 days')))[0];
        $params['data_to'] = explode(' ', $this->utilityService->date(time()))[0];
        return $params;
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
                'coupon_id' => $order->getCouponId(),
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
                'coupon_id' => $order['coupon_id'],
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
        $order["total_amount_view"] = $order['total_amount']  ;

        return ($order);
    }
}
