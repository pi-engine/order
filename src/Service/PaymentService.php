<?php

namespace Order\Service;

use Content\Service\ItemService;
use IntlDateFormatter;
use Order\Repository\OrderRepositoryInterface;
use User\Service\AccountService;
use User\Service\UtilityService;
use function var_dump;

class PaymentService implements ServiceInterface
{
    /* @var OrderRepositoryInterface */
    protected OrderRepositoryInterface $orderRepository;

    protected ItemService $contentItemService;

    protected AccountService $accountService;
    protected UtilityService $utilityService;
//    protected OrderService $orderService;

    /* @var array */
    protected array $config;

    /**
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
//        OrderService             $orderService,
        ItemService              $contentItemService,
        AccountService           $accountService,
        UtilityService           $utilityService,
                                 $config,
    )
    {
        $this->orderRepository = $orderRepository;
//        $this->orderService = $orderService;
        $this->contentItemService = $contentItemService;
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->config = $config;
    }

    ///TODO: remove this method
    public function createLink(object|array $requestBody, mixed $account)
    {
        $order = $this->orderService->getOrder($requestBody, $account);
        $data = array(
            "merchant_id" => $this->config['gateway']['zarinpal']['merchant_id'],
            "amount" => max($order['total_amount'], 1000),
            "callback_url" => $this->config['back_url'],
            "description" => "خرید تست",
            "metadata" => ["order_slug" => $order['slug'], "user_id" => $order['user_id']],
        );
        $jsonData = json_encode($data);
        $ch = curl_init($this->config['gateway']['zarinpal']['url']['request']);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));

        $result = curl_exec($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);

        return $result;
    }


    public function buildLink($order)
    {
        $data = array(
            "merchant_id" => $this->config['gateway']['zarinpal']['merchant_id'],
            "amount" => ($order['total_amount'] * 10),
            "callback_url" => $this->config['callback_url'] . $order['slug'],
            "description" => "خرید تست",
            "metadata" => ["order_slug" => $order['slug'], "user_id" => $order['user_id']],
        );
        $jsonData = json_encode($data);
        $ch = curl_init($this->config['gateway']['zarinpal']['url']['request']);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            )
        );

        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);


        $url = "/";
        $authority = "kerloper";
        if (!$err) {
            $authority = $result['data']["authority"];
            $url = ($this->config['gateway']['zarinpal']['url']['pg']) . $authority;
        }

        return [
            'authority' => $authority,
            'url' => $url,
            'amount' => ($order['total_amount'] * 10),
            'status' => 'waiting',
            'time_create' => time(),
            'time_update' => 0,
            'time_delete' => 0,
            'result' => null,
        ];
    }

    public function verifyPayment(array $order, array $params)
    {

        $data = array("merchant_id" => $this->config['gateway']['zarinpal']['merchant_id'], "authority" => $params['authority'] ?? '', "amount" => ($order['total_amount']*10) ?? 0);
        $jsonData = json_encode($data);
        $ch = curl_init($this->config['gateway']['zarinpal']['url']['verify']);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        $err = curl_error($ch);
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);
        if ($err) {
            return [
                'result' => false,
                'data' => null,
                'error' => [
                    'code' => 400,
                    'message' => $err,
                ],
            ];
        } else {
            if ($result['data']['code'] == 100) {
                return [
                    'result' => true,
                    'data' => [
                        'ref_id' => $result['data']['ref_id'],
                        'message' => 'Success. RefID:' . $result['data']['ref_id'],
                    ],
                    'error' => [],
                ];
            } else {
                return [
                    'result' => false,
                    'data' => null,
                    'error' => [
                        'code' => $result['errors']['code'],
                        'message' => $result['errors']['message'],
                    ],
                ];
            }
        }

    }


}
