<?php

namespace Order\Service;

use Content\Service\ItemService;
use IntlDateFormatter;
use Order\Repository\OrderRepositoryInterface;
use stdClass;
use User\Service\AccountService;
use User\Service\UtilityService;
use function var_dump;

class DiscountService implements ServiceInterface
{
    /* @var OrderRepositoryInterface */
    protected OrderRepositoryInterface $orderRepository;
    protected AccountService $accountService;
    protected UtilityService $utilityService;

    /**
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        AccountService           $accountService,
        UtilityService           $utilityService
    )
    {
        $this->orderRepository = $orderRepository;
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
    }

    public function verifyCode(array $params, mixed $account)
    {
        $discount = $this->canonizeDiscount($this->orderRepository->getDiscount(['code' => $params['code'],'status'=>1]));

        if (($discount['count_used'] >= $discount['count_limit']) || empty($discount)) {
            return [
                'result' => false,
                'data' => new stdClass(),
                'status' => 404,
                'error' => [
                    'message' => 'Cant find any code!',
                    'code' => 404
                ],
            ];
        }

        return [
            'result' => true,
            'data' => [
                'code' => $discount['code'],
                'type' => $discount['type'],
                'value' => $discount['value']
            ],
            'status' => 200,
            'error' => new stdClass(),
        ];

    }

    private function canonizeDiscount(object|array $discount)
    {
        if (empty($discount)) {
            return [];
        }

        if (is_object($discount)) {
            $discount = [
                'id' => $discount->getId(),
                'code' => $discount->getCode(),
                'type' => $discount->getType(),
                'value' => $discount->getValue(),
                'rule' => $discount->getRule(),
                'count_limit' => $discount->getCountLimit(),
                'count_used' => $discount->getCountUsed(),
                'status' => $discount->getStatus(),
                'information' => $discount->getInformation(),  // Assuming you have a getInformation() method
                'time_create' => $discount->getTimeCreate(),
                'time_start' => $discount->getTimeStart(),
                'time_update' => $discount->getTimeUpdate(),
                'time_expired' => $discount->getTimeExpired(),
            ];
        } else {
            $discount = [
                'id' => $discount['id'],
                'code' => $discount['code'],
                'type' => $discount['type'],
                'value' => $discount['value'],
                'rule' => $discount['rule'],
                'count_limit' => $discount['count_limit'],
                'count_used' => $discount['count_used'],
                'status' => $discount['status'],
                'information' => $discount['information'],
                'time_create' => $discount['time_create'],
                'time_start' => $discount['time_start'],
                'time_update' => $discount['time_update'],
                'time_expired' => $discount['time_expired']
            ];
        }

        return $discount;
    }


}
