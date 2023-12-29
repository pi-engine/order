<?php

namespace Order\Service;

use Content\Service\ItemService;
use IntlDateFormatter;
use Order\Repository\OrderRepositoryInterface;
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
        return [];
    }


}
