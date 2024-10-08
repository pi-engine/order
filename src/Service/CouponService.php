<?php

namespace Order\Service;

use Content\Service\ItemService;
use IntlDateFormatter;
use Order\Repository\OrderRepositoryInterface;
use stdClass;
use User\Service\AccountService;
use User\Service\UtilityService;
use function var_dump;

class CouponService implements ServiceInterface
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

    public function getCoupon(array $params, mixed $account): array
    {
        $discount = $this->canonizeCoupon($this->orderRepository->getCoupon(['code' => $params['code'], 'status' => 1]));
        if (empty($discount)) {
            return [];
        }
        if (($discount['count_used'] >= $discount['count_limit'])) {
            return [];
        }
        return $discount;
    }

    public function verifyCode(array $params, mixed $account): array
    {
        $discount = $this->canonizeCoupon($this->orderRepository->getCoupon(['code' => $params['code'], 'status' => 1]));

        if (empty($discount)) {
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
        if (($discount['count_used'] >= $discount['count_limit'])) {
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

    private function canonizeCoupon(object|array $discount): array
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
        $discount['time_expired_view'] = $discount['time_expired'] > 0 ? $this->utilityService->date($discount['time_expired']) : '';
        $discount['time_create_view'] = $this->utilityService->date($discount['time_create']);
        return $discount;
    }

    public function useCoupon(array $discountData, mixed $account): void
    {
        $discountData['count_used'] = $discountData['count_used'] + 1;
        $this->orderRepository->updateCoupon($discountData);
    }

    public function getCouponList(object|array|null $params, mixed $account): array
    {
        $limit = $params['limit'] ?? 1000000;
        $page = $params['page'] ?? 1;
        $order = $params['order'] ?? ['time_create DESC', 'id DESC'];
        $offset = ($page - 1) * $limit;
        $params["order"] = $order;
        $params["offset"] = $offset;
        $params["limit"] = $limit;
        $list = $this->orderRepository->getCouponList($params);
        $discountList = [];
        foreach ($list as $item) {
            $discountList[] = $this->canonizeCoupon($item);
        }
        $count = $this->orderRepository->getCouponCount($params);
        return [
            'result' => true,
            'data' => [
                'list' => $discountList,
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

    public function getCouponAdmin(object|array|null $params, mixed $account): array
    {
        $discount = $this->canonizeCoupon($this->orderRepository->getCoupon($params));
        if (empty($discount)) {
            return [];
        }
        return $discount;
    }

    public function addCoupon(object|array|null $params, mixed $account): array
    {
        $discount = $this->getCouponAdmin(['code' => $params['code'] ?? ''], $account);
        if (!empty($discount)) {
            return [
                'result' => false,
                'data' => new stdClass(),
                'error' => [
                    'message' => 'This code be generated before!',
                    'code' => 500
                ],
            ];
        }
        $params['time_create'] = time();
        if(!isset($params['status'])){
            $params['status'] = 0;
        }
        if(isset($params['time_expired'])){
            $params['time_expired'] = strtotime(
                ($params['time_expired']) != null
                    ? sprintf('%s', $params['time_expired'])
                    : sprintf('%s', date('Y-m-d', strtotime('-1 month')))
            );
        }

        $result = $this->canonizeCoupon($this->orderRepository->addCoupon($params, $account));
        return [
            'result' => true,
            'data' => $result,
            'error' => null,
        ];
    }

    public function updateCoupon(array $params, mixed $account): array
    {
        $listParams['time_update'] = time();
        $listParams['code'] = $params['code'] ?? '';
        if (isset($params['count_limit']) && !empty($params['count_limit'])) {
            $listParams['count_limit'] = $params['count_limit'];
        }
        if (isset($params['status']) && in_array($params['status'], [0, 1])) {
            $listParams['status'] = $params['status'];
        }

        if(isset($params['time_expired'])){
            $listParams['time_expired'] = strtotime(sprintf('%s', $params['time_expired']));
        }

        return $this->canonizeCoupon($this->orderRepository->updateCoupon($listParams));
    }


}
