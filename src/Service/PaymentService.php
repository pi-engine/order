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
    protected OrderService $orderService;

    /* @var array */
    protected array $config;

    /**
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderService             $orderService,
        ItemService              $contentItemService,
        AccountService           $accountService,
        UtilityService           $utilityService,
                                 $config,
    )
    {
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        $this->contentItemService = $contentItemService;
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->config = $config;
    }

    public function createLink(object|array $requestBody, mixed $account)
    {
        $order = $this->orderService->getOrder($requestBody, $account);
        return $this->config;
        $data = array("merchant_id" => $this->config[''],
        "amount" => 1000,
        "callback_url" => "http://www.yoursite.com/verify.php",
        "description" => "خرید تست",
        "metadata" => [ "email" => "info@email.com","mobile"=>"09121234567"],
    );
        $jsonData = json_encode($data);
        $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);


//
//        if ($err) {
//            echo "cURL Error #:" . $err;
//        } else {
//            if (empty($result['errors'])) {
//                if ($result['data']['code'] == 100) {
//                    header('Location: https://www.zarinpal.com/pg/StartPay/' . $result['data']["authority"]);
//                }
//            } else {
//                echo'Error Code: ' . $result['errors']['code'];
//                echo'message: ' .  $result['errors']['message'];
//
//            }
//        }
        return 'createLink';
    }


}
